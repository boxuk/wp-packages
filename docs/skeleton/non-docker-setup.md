# Non Docker Setup

Generally, we do not offer support for non-dockerised setups. However, running without docker should be fairly straightforward if you're familiar with running WordPress locally. You'll require PHP & Composer, a MySQL (or equivalent) server, and a web-server (Apache, Nginx etc). Set the document-root to the root of the project, and ensure your DB credentials are added to the relevant sections of your `.env` file. 
