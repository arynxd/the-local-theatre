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
- [ ] Abstract backend IO operations (reading files, json decoding / encoding)
  - [x] Add JSON stuff to map
  - File IO does not need abstraction
- [ ] Unify codebase (default export, naming)
- [ ] Change CORS policy in backend
- [ ] Make comments a separate page (so that users can click back to go back to the post)
- [ ] Add docs to backend
- [ ] Add docs to frontend
- [ ] Implement database integration in backend


## Feature List 
- [x] Login
- [x] Logout
- [x] Post page
- [ ] Comment list
- [ ] Comment add
- [ ] Comment remove
- [ ] Moderation log
- [ ] User promotion
- [ ] User demotion
- [ ] Post list page

## Ideas

- CAPTCHA integration
- Ratelimiting
- 2FA

## Useful Resources

- php -S localhost:8000 api.php (local php server with hot reload)
- https://www.php.net/manual/en/function.com-create-guid.php
- https://dbdiagram.io/d/617c73c7fa17df5ea6759df1
- https://ui.dev/react-router-url-parameters/ (for post rendering)

