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
- [x] Add interface to frontend logger
- [x] Make home page stay above of footer
- [x] Create insert SQL scripts
- [x] Change frontend inline buttons to blue
- [x] Handle backend errors in frontend components
- [x] Perm checks for restricted pages on frontend
- [x] Add more data to navbar profile image
- [x] Unify shadows
- [x] Fix comments / posts not being updated in UI when they are changed 
- [x] Colour icons
- [ ] Change CORS policy in backend
- [ ] Add docs to backend
- [ ] Add docs to frontend
- [ ] Cache param sources in backend for perf
- [ ] Abstract backend DB calls
- [ ] Animate all moving elements
- [ ] Use useCallback for callback functions
- [ ] Reactive cache implementation
- [ ] Loading spinner on submit buttons
- [ ] Colour icons
- [ ] Cache for blogs
- [ ] Context menu component
- [ ] Changeable avatars
- [ ] Improve backend response api
- [ ] Input validation for edits
- [ ] Moderator deleted comments do not update frontend cache
  - [ ] Clear caches when we logout
- [ ] Cache images by md5 (or similar) => b64
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
- [x] Footer
- [x] 404 Page
- [x] Editing of comments
- [x] Deletion of posts 
- [x] Editing of post
- [ ] Moderation log

## Ideas

- CAPTCHA integration
- Ratelimiting
- 2FA
- Intelligent autoloader for namespaces

## Useful Resources

- php -S localhost:8000 api.php (local php server with hot reload)
- https://dbdiagram.io/d/617c73c7fa17df5ea6759df1
- https://heroicons.dev/

