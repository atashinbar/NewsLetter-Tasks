# WordPress Test Task



## Added Files

- `download-center.js` in `themes\n2go-2014\scripts\download-center` to handle AJAX and some features
- `n2go-download-center.css` in `themes\n2go-2014\visual-composer\styles` to handle CSS
- `download-center.php` in `themes\n2go-2014`

## Changed Files

- `functions.php` line 1520 to end
  -- Added AJAX function
  -- Added Visual Composer shortcode function
- `download-center.php` in `themes\n2go-2014`
  -- Added some plugins and editions to "Amazon" section for testing
  -- Important Change: Added `editionFlag` to plugins to clarify the edition

## Updated File
- `demo.sql`

## Note: 
- I created a zip file contain all chaneged/added files `download-center.zip`

## Tasks

- [ ] Run WordPress docker container
- [ ] Get access to WordPress backend
- [ ] Repair and update WordPress system
- [ ] Rewrite the page at `/download-center/` so that:
  - [ ] ..the page looks and functions roughly like the design in `download-center.png`
    - ignore missing navigation menus and other stuff in header/footer (the objective is the page content)
    - use regular select boxes (with `optgroups`), no need for fancy UX
  - [ ] ..the items in the page are no longer hard-coded in content but come from `download-center.php` (in the future the source of the data will be an API endpoint, for now use the sample data returned by that file)
  - Important hint regarding the business data (returned by `download-center.php`):
    - `Integration` represents a 3rd party system  (eg Magento) that is somehow supported.
    - `IntegrationSystem` represents a version or version range (eg Magento 1) of the 3rd party system (listed in the left dropdowns).
    - `IntegrationPlugin` is a plugin with a particular version that is supported by an integration system (listed in the right dropdowns).
    - `IntegrationConnector` is not displayed on, nor is it useful for the download page.
  - Regarding the dropdowns: changing the *system* (1st/left dropdown) should change the list of available *plugins* (2nd/right dropdown).
    In other words, the left dropdown should affect the right dropdown.

## Things to Consider
- Coding style, especially consistency
- Modern practices eg; using modern HTML tags and PHP constructs
- System design, eg; reusability, avoiding conflicts, basic design patterns
- Logic, eg; easy to understand, self-explanatory/documenting code

## Deliverables

- The provided files with any modifications
- Remember that the database state is not automatically recorded... you have a few options:
  - update `dump.sql` manually
  - overwrite `dump.sql` with a new dump
  - provide a `migrate.sql` with just your changes

## Questions?

Please direct any feedback and questions to <a href="mailto:sciberras@newsletter2go.com?subject=WordPress+Test+Task" target="_blank">jobs@newsletter2go.com</a>.









