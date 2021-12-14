# The Local Theatre

## TODO

- [x] Implement all backend routes
- [x] Finalise frontend theme
- [x] Create frontend database abstractions
- [x] Implement website assets from zip
- [x] Add objects for API return values, use functions in a factory pattern. - Have a function for common codes, with a
  generic one which accepts a number
- [x] Implement objects for query/JSON params, allow for defaults / optionals
- [x] Change to UUID for keying
- [x] Implement tailwind css
- [x] Use a CSS framework for material design (tailwind)
- [x] Use useContext & an AuthContext component for authentication state management
- [x] Make posts a separate page
- [x] add 20006203 prefix to all parts of the site
- [x] Order entities by creation date
- [x] Abstract backend IO operations (reading files, json decoding / encoding)
  - [x] Add JSON stuff to map
  - File IO does not need abstraction
- [x] Unify codebase (default export, naming)
- [x] Add placeholder divs for async tasks
- [x] .editorconfig
- [x] Implement database integration in backend
- [x] Make loglevel a proper enum (static final instances)
- [x] Upgrade tailwind to v3
- [x] Make home page stay above of footer
- [ ] Change CORS policy in backend
- [ ] Add docs to backend
- [ ] Add docs to frontend
- [ ] Unify shadows
- [ ] Perm checks for restricted pages
- [ ] Change inline buttons to blue
- [ ] Change frontend assertions to use TS 'asserts' keyword
- [ ] Change backend assertions docs to describe types
- [ ] Create insert SQL scripts
- [ ] Handle backend errors in frontend components
- [ ] Proper error codes for backend error responses
- [ ] Cache param sources in backend for perf
- [ ] Make home page stay above of footer
- [ ] Add interface to frontend logger

## Feature List 
- [x] Login
- [x] Logout
- [x] Post page
- [x] Comment list
- [x] Post list page
- [x] Comment remove
- [x] Comment add
- [x] User promotion
- [x] User demotion
- [ ] Moderation log
- [ ] Footer
- [ ] 404 Page

## Ideas

- CAPTCHA integration
- Ratelimiting
- 2FA
- Intelligent autoloader for namespaces

## Useful Resources

- php -S localhost:8000 api.php (local php server with hot reload)
- https://www.php.net/manual/en/function.com-create-guid.php
- https://dbdiagram.io/d/617c73c7fa17df5ea6759df1
- https://ui.dev/react-router-url-parameters/ (for post rendering)

