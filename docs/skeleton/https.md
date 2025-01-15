# HTTPS

As part of the nginx docker container we set up a self-signed certificate. This allows us to access the site locally using HTTPS. We do this, because all our websites are served over HTTPS and so we want to ensure we are doing the same locally.

The issue with self-signed certificates is browsers (quite rightly) deem them unsafe. You should too. **You should only bypass the security warnings of your browser if you are working on a secure network**.

## Using a local certificate authority (CA)

You should not use a local certificate authority to have a signed certificate that is validated. A local CA will contaminate your trusted CAs. This means, if your local environment is compromised, or mkcert, then your entire HTTPS trust chain can easily be compromised too. It is for this reason we do not set this up by default and cannot recommend this approach.

If you require a trusted local certificate, you should raise this with your project lead to outline the reasons for the requirements and discuss potential solutions. Setting up a local CA should be considered a last resort, though documented solutions to do this are easily found online you should carefuly consider the risks. 
