# WP Feature Flags

A plugin used to manage the publishing of features.

## Use case

At [BoxUK](https://boxuk.com), we release code behind feature flags. There are [lots of reasons](https://www.boxuk.com/insight/coding-with-feature-flags/) for this, but it mainly allows us to release code more frequently and at lower risk.

We use flags to slowly roll out functionality in increments, across environments. They also let us hide features without a code deploy if we spot bugs.

## Registering a flag

Registering flags is handled using the `FlagRegister` singleton calling it's `register_flag()` method. It's recommended you load flags as early as possible in the load order, either by hooking to `muplugins_loaded`, or even calling the registration method un-hooked so that the flag registry can be populated as early as possible. We recommend that all flags are loaded before the `init` hook so that reading the flag status can be added after this. 

A simple flag registration looks like this:

```php
\BoxUk\WpFeatureFlags\FlagRegister::instance()
	->register_flag(
		new \BoxUk\WpFeatureFlags\Flag(
			'experiment-19', // key
			'Experiment 19', // label
			'An experimental feature that we want to control the release of.', // description
			new \DateTime( '2020-03-23' ), // creation-date
			'experiments', // group-name
			false, // stable
			true // enforced
		)
	)->register_flag(
		new \BoxUk\WpFeatureFlags\Flag(
			'example-flag-2',
			'Example Flag 2',
			'Another example feature flag',
			null,
			'All',
			false,
			true
		)
	);
```

There's also a `register_flags()` where you can pass an array of `Flag` objects. 

A new flag instance can be added with the following properties: 

* **key (text string)** - The unique key for the flag.
* **name (text string)** - the briefest possible summary of what the flag does
* **description (text string)** - a bit more detail about what the flag does, or why it exists

There are extra attributes that can be set in this array that give you more control over your flags.

* **created (DateTime)** - the date you decided to add this flag. This will help with technical debt management. Defaults to `null`. 
* **group (text string)** - to make related flags easier to find, you can group them together by giving them the same group name. 
* **stable (true/false)** - whether the flag is stable enough to be published. This is `true` by default.
* **enforced (true/false)** - flags can be forced into a published state by setting this to `true`. This defaults to `false`.

You could use more complex logic to determine if a flag is stable or enforced, such as by using functions like `wp_get_environment_type()`, but bare in mind these functions may not be ready until later in the loading sequence and as such you may want to hook your registration to a hook such as `muplugins_loaded`. 

## Using Flags

Ideally reading a flags status shouldn't be used before the `init` hook, and registration should happen before that point. This is to ensure that all flags have been registered and that the current user is logged in so we can determine if a user does have access. 

The simplest check would be to use the `enabled()` method, which takes into consideration if a flag is enforced, stable or enabled on a per-user basis to give a valid answer. 

```php
\BoxUk\WpFeatureFlags\FlagRegister::instance()->get_flag( 'example-flag-2' )->enabled();
```

Alternatively, all the other flag properties can be checked independently if you want more fine-control of the state of your flag. 

```php
\BoxUk\WpFeatureFlags\FlagRegister::instance()->get_flag( 'example-flag-2' )->is_published(); // If the flag has been specifically published. This will return false for enforced-flags
\BoxUk\WpFeatureFlags\FlagRegister::instance()->get_flag( 'example-flag-2' )->enabled_for_user( $user_id ); // if the flag is in enabled for a specific user
\BoxUk\WpFeatureFlags\FlagRegister::instance()->get_flag( 'example-flag-2' )->is_stable();
\BoxUk\WpFeatureFlags\FlagRegister::instance()->get_flag( 'example-flag-2' )->is_enforced();
```

### Load Order

If you access flags before the `init` hook you will be able to read the state but this will very much depend on the load-order. In theory you can read a flag status as soon as you have registered the flag, since the underlying code only requires access to `get_option()` and `get_usermeta()`. However, to get the user-based value for flags requires a user-id and `get_current_user_id()` is not available until after `init`, so results will vary depending on if a user-ID is required. 

```php
/** Instantiate a Flag object **/
$flag = new \BoxUk\WpFeatureFlags\Flag(
	'example-flag-3',
	'Example Flag 3',
	'Another example feature flag',
	null,
	'All',
	false,
	true
);

/** Register the Flag **/
\BoxUk\WpFeatureFlags\FlagRegister::instance()->register_flag( $flag ); 


$user_id = 123; // A known user ID (ie, not `get_current_user_id()`

$published = $flag->is_published(); // will work immediately. 
$published = $flag->enabled_for_user(); // will return false until `init` because current user ID is not known before `init`. 
$published = $flag->enabled_for_user( $user_id ); // will work, since we don't need to lookup the current user. 
$published = $flag->enabled(); // will vary - if the flag is enforced, unstable or published, this will work as expected. If none of the other rules apply, it fallsback to checking current user, so will return false before the `init` hook because we don't know the current user ID.
$published = $flag->enabled( $user_id ); // As above - will work as expected. 
```

As you can see, in most instances you may want to wait for the `init` hook before checking a flag's status since any users who are opted in to the flag will not be enabled. This may also cause complexities if you're using the flag check in multiple points in the firing sequence in a WP application and the published state might change depending on your load state. 


## Contributing

Please do not submit any Pull Requests here. They will be closed.
---

Please submit your PR here instead: https://github.com/boxuk/wp-packages

This repository is what we call a "subtree split": a read-only subset of that main repository.
We're looking forward to your PR there!
