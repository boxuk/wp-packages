import { Flag, ApiDate } from '../types';

export const toDate = ( date?: ApiDate | null ): Date | undefined =>
	date && date.date ? new Date( date.date ) : undefined;

const sanitizeString = ( value: unknown ): string | undefined | null => {
	if ( typeof value === 'string' || value === undefined || value === null ) {
		return value;
	}

	return undefined;
};

const sanitizeBoolean = ( value: unknown ): boolean | undefined | null => {
	if ( typeof value === 'boolean' || value === undefined || value === null ) {
		return value;
	}

	return undefined;
};

export const sanitizeFlag = ( data: Record< string, any > ): Flag => ( {
	key: sanitizeString( data.key ),
	name: sanitizeString( data.name ),
	description: sanitizeString( data.description ),
	created: data.created,
	group: sanitizeString( data.group ),
	enforced: sanitizeBoolean( data.enforced ),
	stable: sanitizeBoolean( data.stable ),
	is_published: sanitizeBoolean( data.is_published ),
	users: data.users,
} );
