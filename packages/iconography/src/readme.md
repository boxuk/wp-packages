# Iconography

The data for the iconography is defined here _instead_ of deriving it directly from
the MUI-Symbols package. This is because if we import all the MUI-Symbols the bundle
size is _massive_ where we don't actually need any of the SVG elements. As a result, 
we define the names of all the supported icons. This package will need to be updated
periodically to ensure it is in sync with the MUI-Symbols package.
