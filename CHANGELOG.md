# Changelog



## [Version 0.0.1] - 9-18-2023
### Added
- Added the electrik config for the electrik website in `002-electrik.conf`.
- Added mysqlDB.
- Created the front end of the website with pages.


### Updated
- Updated `aboutus.php`.
- Updated `dashnav.php`.
- Updated `footer.php`.
- Updated `index.php` (removed carousel captions).
- Updated `login.php` (redesigned login form).
- Updated `logout.php`.
- Updated `navbar.php`.
- Updated `register.php` (redesigned register form).
- Updated `terms.php`.
- Updated `validateLogin.php`.
- Updated `user_register.php`.

### Removed
- Removed indent in `002-electrik-ssl.conf`.
- Removed extra comments in `verify_user_session.php`.


### Moved
- Moved JS code in `dashboard.php` into `dashboard.js`.

### Fixed
- Fixed auth changes in `error.log`.
- Fixed mysqli changes in `resetpassword.php`.
- Fixed styling issues in `style.css`.
- Fixed password reset implementation progress in `forgotpassword.html`.
- Fixed salting in `updatepassword.php`.

### Miscellaneous
- Added a new CSS file `style1.css`.
- Added a logout link to the `contact.html` page.



## [Version 0.0.2] - 09-25-2023

### Updated
- Updated `ubuntu_vm_dependencies.md`.
- Updated `setup_web_server.md` (created setup web server docs).
- Updated `mysqlDBsetup.md`.

### Fixed
- Fixed the address for the VM in `rabbitmq_lib`.

### Renamed
- Renamed the database listener program to `dbListener.php`.

### Created
- Created the database listener server program in `sample_env.ini`.


## [Version 0.0.3] - 10-10-2023

### Changes
- rabbitmq_lib: Corrected the address for the VM
- dbListener.php: Rename the DB listener program
- sample_env.ini: Created DB listener server program

### Updated
- 002-electrik-ssl.conf: Remove indent
- error.log: Auth changes
- style.css: Updated style.css
- ubuntu_vm_dependencies.md: Update ubuntu_vm_dependencies.md
- setup_web_server.md: Created setup web server docs
- mysqlDBsetup.md: Update mysqlDBsetup.md

### Added
- 002-electrik.conf: Added the electrik config for the electrik website
- contact.html: Added a logout link to the page
- forgotpassword.html: Password reset implementation progress
- style1.css: Added a new CSS
- sample_env.ini: Created DB listener server program
- setup_web_server.md: Created setup web server docs

### Removed
- verify_user_session.php: Removed extra comments

### Moved
- dashboard.php: Set height for cards
- dashboard.js: Moved JS code in dashboard.php into dashboard.js

### Redesigned
- register.php: Redesigned register.php
  


## [Version 0.0.4] - 11-08-2023 


### Added
- Redesign Website:
  - The entire website has undergone a significant redesign to improve its aesthetics and consistency. The redesign aims to address visual aspects, making the website more appealing. Wireframes were consulted to guide the new look.

- Bootstrap CSS Framework:
  - Implemented the Bootstrap CSS framework throughout the website. This addition enhances the styling and layout, ensuring a modern and polished appearance.

- Responsive Design Compliance:
  - Ensured the website complies with responsive design principles. Utilized Bootstrap's responsive grid system to optimize the user experience across various devices.

- Terms of Service Page:
  - Added a new "Terms of Service" page to the website. This provides users with essential information and guidelines.

### Removed
- Contact Page:
  - The "Contact" page has been removed from the redesign. This decision aligns with the overall restructuring of the website.

### Updated
- Head Tag Standardization:
  - Ensured consistency across all HTML pages by including the `<head>` tag with the important viewport meta tag:
   

- Navigation Bar and Footer:
  - Implemented a standardized approach to include the Navigation Bar and Footer on multiple web pages. This enhances user navigation and maintains a cohesive user interface throughout the site.





## [Version 0.0.5] - 11-12-2023 

### Added
- Added a step with the link to the HTTPS version of the website in `setup_https_for_website.md`.

### Updated
- Color-coded the word "Replica" in `database_replication.md`.
- Fixed the Markdown format in `steam_api.md`.

### Implemented
- Implemented the Distributed Logging System feature.


## [Version 0.0.6] - 11-14-2023

### Added
- Personal Deliverables section
- Required Steam API tasks:
  - GetAppList: GET- GetAppList
  - GetOwnedGames: GET - GetOwnedGames

### Tasks
- Ability to recommend games to people
- Ability for people to rate games in their library
- Ability to search through and view games and their library
- Review on games
- Ability to see other players' reviews

### Features
- Systemd - Startup Services
- Deployment feature
- Hot Standby
- Firewalls
- 3 Clusters (Dev, QA, and Prod)
- DMZ feature



## [Version 0.0.7] - 12-20-2023


### Completed Deliverables
#### Common Deliverables
- HTTPS (Video Proof) - [Checked]
- Hash Passwords (Video Proof) - [Checked]
- Responsive Design (Video Proof) - [Checked]
- DB replication (Video Proof) - [Checked]
- systemd (Screenshot Proof) [Checked]
- Firewalls [Production] (Screenshot Proof) [Checked]
- 2FA (Video Proof) [Checked]

#### Personal Deliverables
- Ability to recommend games to people (Video Proof) [Checked]
- Ability for people to rate games in their library (Video Proof) [Checked]
- Ability to search through and view games and their library (Video Proof) [Checked]
- Review on games (Video Proof) [Checked]
- Ability to see other players' reviews (Video Proof) [Checked]

### Deliverables In Progress
- 3 clusters (Dev, QA, and Prod)
- NAGIOS

### Deliverables To Do
#### Common Deliverables
- Deployment
- Hot standby

#### Personal Deliverables
- Watchlist for upcoming games (Calendar, or Push Notifications)






## [Version 0.1.0]
### Closed Issues
- Redirect to `index.php` enhancement
- Firewalls
- DB replication documentation feature
  - 2 tasks
- Systemd - Startup Services feature
- Setup HTTPS for the website enhancement
  - 2 tasks done
- Personal Deliverables feature
  - 5 of 6 tasks
- Steam API Documentation documentation
- Redesign Website enhancement feature
  - 4 tasks done
- Local Access to MySQL from RabbitMQ enhancement
- Password tweaks
- RabbitMQ Queuing System feature
- Authentication Forget Password feature
- Cookie Session feature
  - 2 tasks
- Ubuntu VM Dependencies documentation
- Character Length for passHash is too small bug enhancement
  - 3 tasks done
- RabbitMQ Documentation documentation
- Setup Authentication Pages
  - 2 tasks done
- Build the webpage
- Fix to MySQL setup documentation
- Setup Website feature




