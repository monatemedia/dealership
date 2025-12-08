<!-- Improved compatibility of back to top link: See: https://github.com/othneildrew/Best-README-Template/pull/73 -->
<a id="readme-top"></a>
<!--
*** Thanks for checking out the Best-README-Template. If you have a suggestion
*** that would make this better, please fork the repo and create a pull request
*** or simply open an issue with the tag "enhancement".
*** Don't forget to give the project a star!
*** Thanks again! Now go create something AMAZING! :D
-->



<!-- PROJECT SHIELDS -->
<!--
*** I'm using markdown "reference style" links for readability.
*** Reference links are enclosed in brackets [ ] instead of parentheses ( ).
*** See the bottom of this document for the declaration of the reference variables
*** for contributors-url, forks-url, etc. This is an optional, concise syntax you may use.
*** https://www.markdownguide.org/basic-syntax/#reference-style-links
-->
[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![project_license][license-shield]][license-url]
[![LinkedIn][linkedin-shield]][linkedin-url]



<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="https://github.com/monatemedia/dealership">
    <img src="images/logoipsum.svg" alt="Logo" height="80">
  </a>

<h3 align="center">Dealership</h3>

  <p align="center">
    A vehicle marketplace that connects buyers and sellers through a user-friendly web based application. It allows dealerships and individual sellers to list vehicles with detailed specifications, images, and pricing, while providing buyers with powerful search and filtering tools to find the right vehicle. The application supports account management, inventory tracking, and secure communication between buyers and sellers, ensuring a streamlined and efficient vehicle marketplace experience.
    <br />
    <a href="https://github.com/monatemedia/dealership"><strong>Explore the docs »</strong></a>
    <br />
    <br />
    <a href="https://github.com/monatemedia/dealership">View Demo</a>
    &middot;
    <a href="https://github.com/monatemedia/dealership/issues/new?labels=bug&template=bug-report---.md">Report Bug</a>
    &middot;
    <a href="https://github.com/monatemedia/dealership/issues/new?labels=enhancement&template=feature-request---.md">Request Feature</a>
  </p>
</div>



<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a></li>
    <li><a href="#roadmap">Roadmap</a></li>
    <li><a href="#contributing">Contributing</a></li>
    <li><a href="#license">License</a></li>
    <li><a href="#contact">Contact</a></li>
    <li><a href="#acknowledgments">Acknowledgments</a></li>
  </ol>
</details>



<!-- ABOUT THE PROJECT -->
## About The Project

[![Product Name Screen Shot][product-screenshot]](https://example.com)

Dealership is a SaaS that connects buyers and sellers of bikes, cars, and commercial vehicles.

<p align="right">(<a href="#readme-top">back to top</a>)</p>



### Built With

  [![Laravel][Laravel.com]][Laravel-url]

  [![AlpineJS][Alpine.js]][Alpine.js-url]

  [![Postgres][Postgresql.org]][Postgresql-url]

  [![Python][Python.org]][Python.org-url]

  [![GitHub][GitHub.com]][GitHub.com-url]

  [![Docker][Docker]][Docker-url]

  [![GitHub-Actions][GitHub-Actions]][GitHub-Actions-url]

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- GETTING STARTED -->
## Getting Started

To get started locally, follow these instructions

### Prerequisites

When running the application you need to have the following installed

* PHP version > 8.2.12
* Composer version > 2.7.1
* Laravel version > 12.37.0
* Node version > 22.17.1
* PostgreSQL version > 18 with PostGIS enabled

#### Required PHP Extensions

##### Install on Debian Based Linux (Ubuntu, Mint, Zorin)

  ```sh
  # ====================================
  # Debian Family - uses apt package manager
  # ====================================

  # Update package list and install PHP GD extension
  sudo apt update && sudo apt install -y php-gd

  # Install image optimization tools
  sudo apt install -y jpegoptim optipng pngquant gifsicle webp

  # Install SVGO (requires Node.js)
  sudo apt install -y nodejs npm
  sudo npm install -g svgo

  # Verify installations
  php -m | grep gd
  jpegoptim --version
  optipng -v
  pngquant --version
  gifsicle --version
  cwebp -version
  svgo --version
  ```

##### Install on Mac

  ```sh
  # ====================================
  # macOS - uses Homebrew
  # ====================================

  # Install Homebrew if not already installed
  # /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

  # Install PHP (includes GD extension)
  brew install php

  # Install image optimization tools
  brew install jpegoptim optipng pngquant gifsicle webp

  # Install SVGO (requires Node.js)
  brew install node
  npm install -g svgo

  # Verify installations
  php -m | grep gd
  jpegoptim --version
  optipng -v
  pngquant --version
  gifsicle --version
  cwebp -version
  svgo --version
  ```

##### Install on Windows

  ```sh
  # Install PHP (includes GD extension by default)
  choco install php -y

  # Install image optimization tools
  choco install jpegoptim -y
  choco install optipng -y
  choco install pngquant -y
  choco install gifsicle -y
  choco install webp -y

  # Note: SVGO requires Node.js
  choco install nodejs -y
  npm install -g svgo

  # Refresh environment variables
  refreshenv

  # Verify installations
  php -m | findstr gd
  jpegoptim --version
  optipng -v
  pngquant --version
  gifsicle --version
  cwebp -version
  svgo --version
  ```

---

#### Required PostGIS Extensions

To enable the PostGIS extension in PostgreSQL, connect to your database with a superuser account and run the SQL command:

  ```sh
  # Enable postgis
  CREATE EXTENSION postgis;
  ```

You can do this in a SQL client like psql or a graphical tool such as pgAdmin.

---

### Create `.env` File

```sh
# Local Development with Postgres and GIS Enabled.   
cp .env.local .env
```

---

### Running The Seeders

- **For local development (App Structure):** Run the standard command to seed necessary application functionality, such as the database structure and content for dropdown menus from configuration files. This process does not create fake demo data.

```sh
# Seed the data
php artisan migrate:fresh --seed
```

- **For local development (Demo Data):** Use the custom db:demo command to populate the database with fake user, vehicle, and image records for development and testing. This command runs the DemoDataSeeder.

```sh
# Seed a standard set of demo data
php artisan db:demo
```

This command also accepts an optional --count option to run the seeder multiple times.

```sh
# Run the seeder 10 times to generate more data
php artisan db:demo --count=10
```

---

### How to Start The App Locally

- In the first terminal run

```sh
# Start the PHP server
php artisan serve
```

- In a second terminal run

```sh
# Start the Vite dev server
npm run build && npm run dev
```

- In a third terminal run

```sh
# Start the queue worker
php artisan queue:work
```

- In a fourth terminal run

```sh
# Start Typesense and import data
php artisan typesense:start
php artisan typesense:create-collections --force --import
```

---

#### Typesense Artisan Commands

##### Quick Start 🚀

```bash
# 1. Start Typesense container
php artisan typesense:start

# 2. Create collections and import data
php artisan typesense:create-collections --force --import

# 3. Verify everything is working
php artisan typesense:status
```

---

##### Core Commands

| Command | Description |
| :--- | :--- |
| `php artisan typesense:start` | Starts the Typesense Docker container. Creates new container if it doesn't exist, or starts existing one. Waits for API to be healthy before returning. |
| `php artisan typesense:stop` | Stops the running Typesense container without removing it. Data is preserved. |
| `php artisan typesense:status` | Shows Docker container status, API health, and lists all collections with document/field counts. Use this to verify your setup. |

---

##### Data Management 🗃️

| Command | Description |
| :--- | :--- |
| `php artisan typesense:create-collections` | Creates collection schemas from `config/scout.php`. Use alone to create empty collections. |
| `php artisan typesense:create-collections --force` | **Recreates** collections by deleting existing ones first. Required when schema changes. |
| `php artisan typesense:create-collections --force --import` | **Recommended:** Recreates schemas AND imports all data with progress bars. Imports in correct dependency order: Manufacturer → Model → Province → City → Vehicle. |
| `php artisan typesense:search "query"` | Test search from command line. Use `--limit=N` to control result count. Great for debugging. |

**Note:** The Vehicle collection includes a `geo_location` geopoint field (25 total fields) for radius-based location filtering.

---

##### Destroy Commands 🛑

⚠️ **Warning:** These commands permanently delete data!

| Command | Description |
| :--- | :--- |
| `php artisan typesense:destroy` | Prompts for confirmation, then removes container. Asks about data volume removal. |
| `php artisan typesense:destroy --force` | Removes container and data volume immediately without prompts. **ALL DATA LOST!** |
| `php artisan typesense:destroy --keep-volume` | Removes container but preserves the data volume for later use. |

---

##### Common Workflows

**Fresh Setup:**
```bash
php artisan typesense:start
php artisan typesense:create-collections --force --import
php artisan typesense:status
```

**After Schema Changes:**
```bash
php artisan typesense:create-collections --force --import
```

**Complete Reset:**
```bash
php artisan typesense:destroy --force
php artisan typesense:start
php artisan typesense:create-collections --force --import
```

**Quick Search Test:**
```bash
php artisan typesense:search "BMW" --limit=5
```

---

##### Configuration

Typesense settings are in `config/scout.php` under `model-settings`. Each model has:
- **collection-schema**: Field definitions and types
- **search-parameters**: Query configuration (query_by, num_typos, etc.)

Environment variables:
```env
TYPESENSE_HOST=dealership-typesense
TYPESENSE_PORT=8108
TYPESENSE_API_KEY=your-secret-key
```

---

##### Troubleshooting

**Container won't start:**
```bash
docker info  # Check Docker is running
docker logs dealership-typesense  # View logs
php artisan typesense:destroy --force  # Reset completely
php artisan typesense:start
```

**No search results:**
```bash
php artisan typesense:status  # Check document counts
php artisan typesense:create-collections --force --import  # Re-import
```

**Schema field mismatch:**
```bash
# Vehicle should show 25 fields (including geo_location)
php artisan typesense:status
# If wrong, recreate:
php artisan typesense:create-collections --force --import
```

<!-- GETTING STARTED WITH DOCKER -->
## 🏗️ Docker Build Environment Control

Our Docker build process is designed to optimize image size for production while allowing the inclusion of development dependencies (like Faker for seeding) in test and staging environments. This is controlled by a single **build argument** in the `Dockerfile`.

### Create `.env` File

Before running any commands, copy your environment configuration:

```sh
# Docker Desktop Development with Containers
cp .env.docker-desktop .env
````

-----

### 📦 Composer Dependencies (`--no-dev`)

By default, our Docker image is built for **production** and excludes all Composer development dependencies (using the `--no-dev` flag) to minimize the final image size and reduce the attack surface.

This behavior is controlled by the `INSTALL_DEV_DEPENDENCIES` build argument, which applies to **all services** built from the main Dockerfile (`dealership-web`, `dealership-queue`, and the one-off `dealership-setup`).

| Scenario | Goal | Recommended Build Command | `INSTALL_DEV_DEPENDENCIES` |
| :--- | :--- | :--- | :--- |
| **Production / Default** | **Exclude** dev packages for small, secure image. | `docker compose build` | `false` (default) |
| **Development Setup** | **Include** dev packages (e.g., Faker) for data generation. | **`docker compose build --build-arg INSTALL_DEV_DEPENDENCIES=true dealership-setup`** | `true` |

#### How It Works

The `Dockerfile` contains logic in the `composer-builder` stage that checks the value of the argument:

```dockerfile
# Dockerfile snippet:
ARG INSTALL_DEV_DEPENDENCIES=false
RUN COMPOSER_INSTALL_FLAGS="..."; \
    if [ "$INSTALL_DEV_DEPENDENCIES" != "true" ]; then \
        COMPOSER_INSTALL_FLAGS="$COMPOSER_INSTALL_FLAGS --no-dev"; \
    fi; \
    composer install ... $COMPOSER_INSTALL_FLAGS
# ...
```

  * When the argument is **omitted** (default `false`), the `--no-dev` flag is added, and dev dependencies are excluded.
  * When the argument is explicitly set to **`true`**, the `--no-dev` flag is skipped, and all dependencies (including Faker) are installed.

-----

## 🚀 Running the Application and Data Setup

### 1\. Start the Core Services

These commands start the persistent infrastructure and application services. They run migrations but **do not** run slow data seeding.

```sh
# Start the core application and infrastructure services (Web, Queue, DB, Typesense)
docker compose up -d
```

### 2\. Generate Development Data (If Needed)

If you need fake data (e.g., 10,000 listings) for local development, you must use the dedicated one-off setup container.

1.  **Build the `dealership-setup` image with dev dependencies** (see table above).
2.  **Run the setup container** to execute the slow data seeding (`db:demo`) and Typesense import.


```sh
# Ensure the image is built with dev dependencies first!
docker compose build --build-arg INSTALL_DEV_DEPENDENCIES=true dealership-setup

# Run the one-off setup container to generate 10,000 listings and import them to Typesense
docker compose run --rm dealership-setup
```

-----

### Installation

1. Get a free API Key at [https://example.com](https://example.com)
2. Clone the repo
   ```sh
   git clone https://github.com/monatemedia/dealership.git
   ```
3. Install NPM packages
   ```sh
   npm install
   ```
4. Enter your API in `config.js`
   ```js
   const API_KEY = 'ENTER YOUR API';
   ```
5. Change git remote url to avoid accidental pushes to base project
   ```sh
   git remote set-url origin monatemedia/dealership
   git remote -v # confirm the changes
   ```

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- USAGE EXAMPLES -->
## Usage

Use this space to show useful examples of how a project can be used. Additional screenshots, code examples and demos work well in this space. You may also link to more resources.

_For more examples, please refer to the [Documentation](https://example.com)_

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- ROADMAP -->
## Roadmap

- [X] Image Processing
  - [X] Create Background Job
  - [X] Compress images to webp
- [X] Alpine Flash Messages
- [X] Sortable Vehicle Image List
- [X] Create Branches
  - [X] `main`
  - [X] `dev`
- [X] Category
    - [X] Category
      - [X] Create Category Feature Branch
      - [X] Refactor Naming: Vehicle → Vehicle
      - [X] Create Categories
      - [X] Vehicle Category Selection Page
      - [X] Display Vehicles by Category
- [X] Convert Seeders into Individual Classes 
  - [X] VehicleCategorySeeder
  - [X] VehicleTypeSeeder
  - [X] FuelTypeSeeder
  - [X] LocationSeeder (for Provinces & Cities)
  - [X] ManufacturerSeeder (for Manufacturers & Models)
  - [X] DemoDataSeeder (for fake Users, Vehicles, Images, etc.)
- [X] Import Make & Model From NHTSA VPIC database
  - [X] Strip Data with `strip_make_model_from_vpic.py` script
  - [X] Insert Data Into DB
  - [X] Update Seeders
  - [X] Update Manufacturer and Model Components
- [X] Import South Africa Locations
  - [X] Update Migrations, Seeders, Models, and Components
  - [X] Import Locations From https://github.com/dirkstrauss/SouthAfrica/
  - [X] Insert Data Into DB
  - [X] Update Seeders
  - [X] Update Location Components
- [X] Change Features Table From Wide Table vs. Narrow Table
  - [X] Update `features` Table
  - [X] Create `feature_vehicle` Pivot Table
  - [X] Update `Feature` Model
  - [X] Update `Vehicle` Model
  - [X] Update Feature Factory
  - [X] Create Config With Default Data
- [X] Category Aware Create Form
  - [X] Accept Categories into Create Form
  - [X] Set Up Categories on create forms
  - [X] Update Categories Title and Paragraph
  - [X] Update Flash Messages
- [X] Make Multipurpose "Mileage" Component
- [X] Style Dropdowns
  - [X] Style Manufacturer Component
  - [X] Style Model Component
  - [X] Style Province Component
  - [X] Style City Component
- [X] Make Dynamic FuelTypes
  - [X] Create Lookups
  - [X] Create Migrations
  - [X] Create Factory
  - [X] Create Seeder
  - [X] Update Model
  - [X] Make Reusable List Component
  - [X] Make Dynamic FuelType Component
- [X] Make VIN Tools
  - [X] VIN Validator
  - [X] VIN Decoder
  - [X] VIN Generator
- [X] Make Docker Container
  - [X] Make Container with PostgreSQL
  - [X] Upload Container To Live Server
  - [X] Map Domain Name
  - [X] Map Email For Account Creation
  - [X] Set Up CI/CD
- [X] Set Up Typesense Search Engine
  - [X] Install Laravel Scout
  - [X] Update Models
- [X] Set Up PostGIS Search
  - [X] Enable PostGIS
  - [X] Add PostGIS Geometry Column
  - [X] Create User Address Functionality
  - [X] Use In App
- [X] Set Up Production Docker Containers for TypeSense
- [X] Set up production CI/CD
- [ ] Get App Amazon SES Ready
  - [ ] Technical Compliance & Feedback Loops
    - [ ] Verify Domain
    - [ ] Set Up DKIM & SPF
    - [ ] Implement Bounce & Complaint Handling
    - [ ] Create a Configuration Set
  - [ ] Website & Legal Readiness
    - [ ] Logo
    - [X] Footer
    - [ ] Fully Functional Website
    - [X] Working Sign-Up Flow
    - [X] Clear Opt-In
    - [ ] Visible Legal Pages
      - [X] Privacy Policy, 
      - [X] Terms of Service, 
      - [ ] Contact Us
    - [ ] Contact Information
      - [ ] Social Media
      - [X] Email for 
        - [X] Webmaster, 
        - [X] No Reply, 
        - [X] Social, 
        - [X] Info, 
        - [X] Edward
  - [ ] Sandbox Testing & Account Health
    - [ ] Test Sending & Receiving
    - [ ] Test Bounce Processing
    - [ ] Ensure Good AWS Health
  - [ ] Application Narrative (The Request Form)
- [ ] Allow Unknown `Manufacturers` and `Models` to be created
- [ ] Bugfix: App CSS to `carType` and `fueltype` components
- [ ] Test Create & Edit Pages
- [ ] Make current Main Categories into **Sections** 
- [ ] Make current Subcategories into **Categories**
- [ ] Add Group term: **Channels**
  - [ ] Individual categories:
    - [ ] Air
    - [ ] Land
    - [ ] Water

See the [open issues](https://github.com/monatemedia/dealership/issues) for a full list of proposed features (and known issues).

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- CONTRIBUTING -->
# Contributing

We use the `GitFlow Branching Model`. To make a contribution, please fork the repo and create a pull request. You can also <a href="https://github.com/monatemedia/dealership/issues/new?labels=bug&template=bug-report---.md">report a bug</a>, or <a href="https://github.com/monatemedia/dealership/issues/new?labels=enhancement&template=feature-request---.md">request a feature</a>.

## GitFlow Branching Model

This project follows the GitFlow branching strategy with automated CI/CD deployments.
The goal is to keep `main` always production-ready while using `dev` as an integration branch.

All work happens in short-lived branches that are deleted after merge.

### Core Branches

**`main`** → always production-ready, deployed code. Automatically builds and deploys to production server when code is pushed or when version tags are created.

**`dev`** → integration branch where features and fixes are merged before going to production. Used for local development on Docker Desktop.

### Short-Lived Branches

Temporary branches, deleted after merge:

- **`feature/<name>`** → for new functionality. Created from `dev`, merged back into `dev`.
- **`bugfix/<name>`** → for fixing bugs. Created from `dev`, merged back into `dev`.
- **`release/<version>`** → staging branch to prepare a version before tagging and merging to `main`. Created from `dev`, merged into both `main` and `dev`. **Automatically deploys to staging environment for testing.**
- **`hotfix/<name>`** → for urgent production fixes. Branched off `main`, merged back to both `main` and `dev`.

### Deployment Environments

- **Production** → Triggered by creating version tags (e.g., `v1.0.0`). Deploys to production server.  Adminer is NOT included in production.
- **Staging** → `release/*` branches automatically deploy to staging environment on VPS for QA/testing.  Adminer is included for database access.
- **Local Development** → `dev` branch for development on Docker Desktop. **Adminer is included.**

### Version Management

This project uses **Git tags** as the source of truth for versioning. All deployments are tracked with semantic versioning (e.g., `v1.0.0`, `v2.1.5`).

**Docker images are tagged with:**
- `:staging` - Latest staging release
- `:production` - Latest production release
- `:v1.0.0` - Specific version number
- `:abc123def` - Git commit SHA (for traceability)

**Image tags are automatically managed** - the `IMAGE_TAG` environment variable in `.env` is updated during deployment.

## Complete Release Workflow Example

Here's a complete example from start to finish:

### Scenario: Releasing version 1.2.0

**Week 1-2: Development**
```bash
# Create and work on features
git checkout dev
git checkout -b feature/vehicle-search
# ... work on feature ...
git push origin feature/vehicle-search
# Merge to dev via PR
```

**Week 3: Prepare Release**
```bash
# Create release branch
git checkout dev
git pull origin dev
git checkout -b release/1.2.0

# Update version numbers in your code
# - composer.json
# - package.json
# - Any version constants

git add .
git commit -m "Bump version to 1.2.0"
git push origin release/1.2.0
```

**🧪 Automatic Staging Deployment happens now**
- **Critical Step:** The CI/CD pipeline automatically runs the dedicated **Essential Setup Service** (`dealership-init`) to execute migrations and mandatory, slow seeders (e.g., car data) before starting the main web application.
- Test thoroughly on staging environment
- Fix any bugs found
- Push bug fixes to `release/1.2.0` branch

**Week 4: Deploy to Production**
```bash
# Step 1: Merge to main
git checkout main
git pull origin main
git merge release/1.2.0
git push origin main

# Step 2: Create and push tag (THIS IS CRITICAL!)
git tag -a v1.2.0 -m "Release 1.2.0: Add vehicle search and favorites"
git push origin v1.2.0

# Step 3: Merge back to dev
git checkout dev
git pull origin dev
git merge release/1.2.0
git push origin dev

# Step 4: Clean up
git branch -d release/1.2.0
git push origin --delete release/1.2.0
```

**🚀 Production Deployment happens automatically**
- GitHub Actions deploys to production
- Visit GitHub Releases to add release notes

**Add Release Notes on GitHub:**
1. Go to: `https://github.com/your-username/dealership/releases/tag/v1.2.0`
2. Click "Edit release"
3. Add details:
   ```markdown
   ## What's New
   - Added advanced vehicle search with filters
   - Implemented user favorites feature
   - Improved search performance by 40%
   
   ## Bug Fixes
   - Fixed pagination on search results
   - Resolved image upload timeout issues
   
   ## Breaking Changes
   None
   
   ## Migration Notes
   No database migrations required
   ```

**Week 4+: Monitor Production**
- Watch for any issues
- Prepare hotfix if critical bugs are found

### If a Critical Bug is Found

```bash
# Create hotfix
git checkout main
git pull origin main
git checkout -b hotfix/fix-search-crash

# Fix the bug, test locally
git add .
git commit -m "Fix search crash on empty query"
git push origin hotfix/fix-search-crash

# Deploy hotfix
git checkout main
git merge hotfix/fix-search-crash
git push origin main

# Create patch version tag
git tag -a v1.2.1 -m "Hotfix 1.2.1: Fix search crash on empty query"
git push origin v1.2.1

# Merge to dev
git checkout dev
git merge hotfix/fix-search-crash
git push origin dev

# Clean up
git branch -d hotfix/fix-search-crash
git push origin --delete hotfix/fix-search-crash
```

---

## Working with Branches

### Core Branches

#### main

Always production-ready. Code here is what's deployed to production.

**Triggers automatic deployment to production when:**
- Code is pushed to `main`
- A version tag is created (e.g., `v1.0.0`)

#### dev

Integration branch. Features and bugfixes merge here before going to production. Used for local development.

---

## Short-Lived Branches

### Feature Branches

For new functionality.
Created from `dev`, merged back into `dev`.

#### CREATE A FEATURE BRANCH

```bash
# Make sure you're on dev
git checkout dev

# Update dev with latest remote
git pull origin dev

# Create the new feature branch
git checkout -b feature/<name>

# work, commit
git push origin feature/<name>

# List all branches that contain the tip commit of your feature branch
git branch --contains feature/<name>
```

#### MERGE FEATURE BRANCH

```bash
# Make sure all your work is committed on the feature branch
git status
git add .
git commit -m "Meaningful Message"   # if needed

# Push the feature branch to remote (first time)
git push -u origin feature/<name>

# Switch to dev
git checkout dev

# Update dev with latest remote
git pull origin dev

# Merge the feature branch
git merge feature/<name>

# Push the merged result
git push origin dev

# List all branches that contain the tip commit of your feature branch
git branch --contains feature/<name>

# View recent history on dev, check for your commits, `q` to quit
git log --oneline --graph --decorate -20

# Delete the local feature branch 
# Use -D to force if branch isn't merged
git branch -d feature/<name>

# Delete the remote feature branch
git push origin --delete feature/<name>

# Get existing local and remote branches
git branch --all
```

#### DELETE AN UNWANTED FEATURE BRANCH

```bash
# Switch back to dev
git checkout dev

# Reset dev to match remote (optional but recommended)
# This ensures dev is exactly as it was before the branch was created.
git fetch origin
git reset --hard origin/dev

# Delete the unwanted branch locally
git branch -D feature/<name>

# Delete the branch remotely
git push origin --delete feature/<name>
```

Merge via Pull Request into `dev`.

---

### Undo Last Commit

For undoing your last commit.

#### UNDO THE LAST COMMIT BUT KEEP YOUR CODE CHANGES (UNCOMMITTED)

```bash
# - The commit is undone
# - All your changes stay staged and ready to recommit
git reset --soft HEAD~1
```

#### UNDO THE LAST COMMIT AND UNSTAGE THE FILES (KEEP IN WORKING DIRECTORY)

```bash
# - The commit is undone
# - Files are unstaged but still modified in your working directory.
git reset --mixed HEAD~1
```

#### COMPLETELY DISCARD THE LAST COMMIT AND ALL ITS CHANGES

```bash
# - The commit and all associated changes are deleted.
# - Your local dev matches the state before that commit.
git reset --hard HEAD~1
```

#### OPTIONAL CLEANUP

```bash
# - If you want to discard both commits and exactly match remote origin/dev
git reset --hard origin/dev
```

---

### Bugfix Branches

For fixing bugs (not urgent production issues).
Created from `dev`, merged back into `dev`.

```bash
git checkout dev
git pull origin dev
git checkout -b bugfix/<name>
# work, commit
git push origin bugfix/<name>
```

Merge via Pull Request into `dev`.

---

### Release Branches

For preparing a version before tagging and merging into production.
Created from `dev`, merged into both `main` and `dev`.

**Release branches are automatically deployed to the staging environment for QA and testing.**

#### CREATE AND DEPLOY TO STAGING

```bash
# Make sure dev is up to date
git checkout dev
git pull origin dev

# Create release branch with version number
git checkout -b release/1.0.0

# Make final tweaks, version bumps
# Update version in composer.json, package.json, etc.

# Push to trigger automatic staging deployment
git push origin release/1.0.0
```

**🧪 Automatic Staging Deployment:**
- GitHub Actions detects the push to `release/*`
- **Critical Step:** The `dealership-init` service runs migrations and mandatory seeders, then exits.
- The main application services (`dealership-web`, `dealership-queue`) are started.
- Health checks are performed after the web server has fully booted.
- Docker image tagged as `:staging` and `:v1.0.0` is deployed.
- Adminer is available for database access
- Server's `.env` is updated with `IMAGE_TAG=v1.0.0`

#### TEST ON STAGING

Visit your staging environment and thoroughly test all functionality.

#### PROMOTE TO PRODUCTION

After successful testing on staging, it's time to deploy to production. Follow these steps carefully:

**Step 1: Merge release to main**
```bash
# Switch to main branch
git checkout main

# Make sure main is up to date
git pull origin main

# Merge the release branch
git merge release/1.0.0

# Push to main
git push origin main
```

**Step 2: Create and push the version tag**

This is **critical** - the tag triggers the production deployment and creates a GitHub Release.

```bash
# Still on main branch
# Create an annotated tag with a message
git tag -a v1.0.0 -m "Release version 1.0.0"

# Push the tag to trigger production deployment
git push origin v1.0.0
```

⚠️ **Important:** Always use the `-a` flag (annotated tag) and include a meaningful message with `-m`.

**Step 3: Merge back to dev**

Keep `dev` updated with the production changes:

```bash
# Switch to dev
git checkout dev

# Make sure dev is up to date
git pull origin dev

# Merge the release branch
git merge release/1.0.0

# Push to dev
git push origin dev
```

**Step 4: Clean up the release branch**
```bash
# Delete the local release branch
git branch -d release/1.0.0

# Delete the remote release branch
git push origin --delete release/1.0.0

# Remove the 'dealership' stack and its containers/volumes
docker rm -f dealership-web dealership-queue dealership-db dealership-typesense dealership-adminer
```

**🚀 Automatic Production Deployment:**
- GitHub Actions detects the `v1.0.0` tag
- Builds Docker image tagged as `:production` and `:v1.0.0`
- Image is deployed to production server automatically
- Adminer is NOT included in production (security)
- Server's `.env` is updated with `IMAGE_TAG=v1.0.0`

**GitHub Release:**
The version tag automatically creates a GitHub Release at `https://github.com/your-username/dealership/releases/tag/v1.0.0`. You can add release notes there describing what's new, what's fixed, and any breaking changes.

**Step 5: Production Server Maintenance (CRITICAL)**
Perform this step periodically or after every major release to prevent disk space issues.

```bash
# 1. Remove the 'dealership' stack and its containers/volumes (Optional)
docker rm -f dealership-web dealership-queue dealership-db dealership-typesense dealership-adminer

# 2. Clean up unused Docker resources (images, stopped containers, build cache)
# This reclaimed 7.241GB last time!
docker system prune -af --volumes

# 3. Vacuum systemd journal (clears large system logs, keeps last 50MB)
sudo journalctl --vacuum-size=50M

# 4. Truncate large application/system log files
sudo truncate -s 0 /var/log/syslog
sudo truncate -s 0 /var/log/kern.log
sudo truncate -s 0 /var/log/auth.log

# 5. Remove old rotated logs
sudo rm -f /var/log/*.gz
sudo rm -f /var/log/*.[0-9]

# 6. Clear package caches
sudo apt-get clean

# 7. Verify free disk space is ample (e.g., Use% < 80%)
df -h /
# 8. Verify inode space (important for many small files)
df -i /
```

This ensures the production server is clean, and prevents disk-full errors that halt deployment (e.g., scp: write remote ... Failure).

---

### Hotfix Branches

For urgent production fixes.
Created from `main`, merged back into both `main` and `dev`.

#### CREATE HOTFIX

```bash
git checkout main
git pull origin main
git checkout -b hotfix/<name>
# work, commit
git push origin hotfix/<name>
```

#### DEPLOY HOTFIX

```bash
# Merge to main
git checkout main
git merge hotfix/<name>
git push origin main

# Create patch version tag
git tag -a v1.0.1 -m "Hotfix: <description>"
git push origin v1.0.1

# Merge back to dev
git checkout dev
git merge hotfix/<name>
git push origin dev

# Delete hotfix branch
git branch -d hotfix/<name>
git push origin --delete hotfix/<name>
```

Merge via Pull Request into both `main` and `dev`.

**Optional: Test on staging before merging**
If needed, you can manually deploy the hotfix branch to staging for testing before merging to production.

---

## Version Management

### Semantic Versioning

We use semantic versioning: `MAJOR.MINOR.PATCH` (e.g., `v1.0.0`, `v2.3.5`)

- **MAJOR** - Breaking changes (e.g., `v1.0.0` → `v2.0.0`)
- **MINOR** - New features (backwards compatible) (e.g., `v1.0.0` → `v1.1.0`)
- **PATCH** - Bug fixes (e.g., `v1.0.0` → `v1.0.1`)

### Git Tagging Quick Reference

**Creating tags:**

```bash
# Annotated tag (REQUIRED - use this!)
git tag -a v1.0.0 -m "Release version 1.0.0"

# Push a specific tag
git push origin v1.0.0

# Push all tags at once (use with caution)
git push origin --tags
```

**Listing tags:**

```bash
# List all tags
git tag

# List tags matching a pattern
git tag -l "v1.*"

# Show tag details
git show v1.0.0
```

**Deleting tags (if you made a mistake):**

```bash
# Delete local tag
git tag -d v1.0.0

# Delete remote tag
git push origin --delete v1.0.0
```

**Common tagging mistakes to avoid:**

❌ **DON'T** create lightweight tags: `git tag v1.0.0` (missing `-a`)

✅ **DO** create annotated tags: `git tag -a v1.0.0 -m "message"`

❌ **DON'T** forget to push tags after creating them

✅ **DO** remember: `git push origin v1.0.0`

❌ **DON'T** use inconsistent version formats (`1.0.0` vs `v1.0.0`)

✅ **DO** always prefix with `v`: `v1.0.0`, `v2.1.5`

### When to Increment Version Numbers

**Increment MAJOR (v1.0.0 → v2.0.0):**
- Breaking API changes
- Major architectural changes
- Database migrations that require manual intervention
- Removing deprecated features

**Increment MINOR (v1.0.0 → v1.1.0):**
- New features added
- New API endpoints
- New functionality that doesn't break existing code
- Regular releases from `release/*` branches

**Increment PATCH (v1.0.0 → v1.0.1):**
- Bug fixes
- Security patches
- Performance improvements
- Hotfixes from `hotfix/*` branches

### Version Numbering Examples

Starting from `v1.0.0`:

```bash
# First feature release
git tag -a v1.1.0 -m "Release 1.1.0: Add vehicle search feature"

# Bug fix
git tag -a v1.1.1 -m "Hotfix 1.1.1: Fix search pagination bug"

# Another feature release
git tag -a v1.2.0 -m "Release 1.2.0: Add user favorites"

# Major breaking change
git tag -a v2.0.0 -m "Release 2.0.0: New API structure (breaking changes)"
```

### Checking Current Version

**On your server:**
```bash
# Check configured version
cat .env | grep IMAGE_TAG

# Check running containers
docker compose ps

# List all available versions
docker images ghcr.io/monatemedia/dealership

# View image details
docker compose config | grep "image:"
```

### Rolling Back to Previous Version

If you need to rollback to a previous version:

```bash
# SSH into your server
cd /path/to/project

# Update IMAGE_TAG in .env
echo "IMAGE_TAG=v1.0.0" >> .env  # Change to desired version

# Restart containers
docker compose down
docker compose up -d

# Or for production (without Adminer)
docker compose up -d dealership-web dealership-queue dealership-db
```

### Running Adminer Locally

For local development with Adminer:

```bash
docker compose --profile dev up -d
```

For staging with Adminer:

```bash
docker compose --profile staging up -d
```

For production (no Adminer):

```bash
docker compose up -d dealership-web dealership-queue dealership-db
```

---

## Standard Workflow

1. Fork or clone the project.

2. Create your branch (`feature/*`, `bugfix/*`, `release/*`, or `hotfix/*`).

3. Commit your changes:
   ```bash
   git commit -m "Meaningful message"
   ```

4. Push to remote:
   ```bash
   git push origin branch-name
   ```

5. Open a Pull Request into the correct target branch.

6. After merge, **deployments happen automatically** via GitHub Actions.

---

## Summary of Branch Sources

- `feature/*` → from `dev`, merge into `dev`.
- `bugfix/*` → from `dev`, merge into `dev`.
- `release/*` → from `dev`, merge into `main` + `dev`. **Auto-deploys to staging.**
- `hotfix/*` → from `main`, merge into `main` + `dev`. **Auto-deploys to production.**

---

## CI/CD Deployment Flow

### Automatic Deployments

| Trigger | Environment | Docker Tags | Adminer |
|---------|-------------|-------------|---------|
| Push to `release/*` | Staging | `:staging`, `:v1.0.0` | ✅ Yes |
| Push to `main` | Production | `:production`, `:v1.0.0` | ❌ No |
| Push tag `v1.0.0` | Production | `:production`, `:v1.0.0` | ❌ No |
| Local dev | Development | `:dev` | ✅ Yes |

### Required GitHub Secrets

**For Staging (Current VPS):**
- `PAT` - GitHub Personal Access Token
- `SSH_PRIVATE_KEY` - SSH key for VPS
- `SSH_HOST` - VPS IP address
- `SSH_USER` - SSH username
- `WORK_DIR` - Project directory path

**For Production (When Ready):**
- `PRODUCTION_SSH_KEY` - SSH key for production server
- `PRODUCTION_HOST` - Production server IP
- `PRODUCTION_USER` - Production SSH username
- `PRODUCTION_WORK_DIR` - Production project directory

### Workflow Location

`.github/workflows/docker-publish.yml`

---

### Deployment Permissions

The CI/CD pipeline automatically handles storage permissions after each deployment. The workflow:

1. Deploys new Docker image
2. Starts containers
3. **Fixes permissions inside the container** (no host permission issues)
4. Performs health check

**Manual Permission Fix (if needed):**
```bash
# Staging
docker exec dealership-web chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
docker exec dealership-web chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Production
docker exec actuallyfind-web chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
docker exec actuallyfind-web chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
```
---

## Best Practices

1. **Always create release branches from dev** - never from feature branches
2. **Test thoroughly on staging** before merging to main
3. **Use semantic versioning** for all releases
4. **Tag releases immediately** after merging to main
5. **Keep release notes** in GitHub Releases
6. **Never push directly to main** - always use pull requests
7. **Clean up branches** after merging
8. **Monitor GitHub Actions** for deployment status
9. **Keep `.env` files secure** - never commit them
10. **Use Adminer only in dev/staging** - never expose in production


<p align="right">(<a href="#readme-top">back to top</a>)</p>

### Top contributors:

<a href="https://github.com/monatemedia/dealership/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=monatemedia/dealership" alt="contrib.rocks image" />
</a>



<!-- LICENSE -->
## License

All rights reserved. See `LICENSE.txt` for more information.

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- CONTACT -->
## Contact

Edward Baitsewe - [@MonateMedia](https://twitter.com/MonateMedia) - edward@monatemedia.com

Project Link: [https://github.com/monatemedia/dealership](https://github.com/monatemedia/dealership)

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- ACKNOWLEDGMENTS -->
## Acknowledgments

* []()
* []()
* []()

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[contributors-shield]: https://img.shields.io/github/contributors/monatemedia/dealership.svg?style=for-the-badge
[contributors-url]: https://github.com/monatemedia/dealership/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/monatemedia/dealership.svg?style=for-the-badge
[forks-url]: https://github.com/monatemedia/dealership/network/members
[stars-shield]: https://img.shields.io/github/stars/monatemedia/dealership.svg?style=for-the-badge
[stars-url]: https://github.com/monatemedia/dealership/stargazers
[issues-shield]: https://img.shields.io/github/issues/monatemedia/dealership.svg?style=for-the-badge
[issues-url]: https://github.com/monatemedia/dealership/issues
[license-shield]: https://img.shields.io/github/license/monatemedia/dealership.svg?style=for-the-badge
[license-url]: https://github.com/monatemedia/dealership/blob/master/LICENSE.txt
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-black.svg?style=for-the-badge&logo=linkedin&colorB=555
[linkedin-url]: https://linkedin.com/in/edwardbaitsewe
[product-screenshot]: images/screenshot.png
[Next.js]: https://img.shields.io/badge/next.js-000000?style=for-the-badge&logo=nextdotjs&logoColor=white
[Laravel.com]: https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white
[Laravel-url]: https://laravel.com
[Alpine.js]: https://img.shields.io/badge/alpinejs-white.svg?style=for-the-badge&logo=alpinedotjs&logoColor=%238BC0D0
[Alpine.js-url]: https://alpinejs.dev/
[Python.org]: https://img.shields.io/badge/python-3670A0?style=for-the-badge&logo=python&logoColor=ffdd54
[Python.org-url]: https://www.python.org/
[GitHub.com]: https://img.shields.io/badge/github-%23121011.svg?style=for-the-badge&logo=github&logoColor=white
[GitHub.com-url]: https://github.com/
[GitHub-Actions]: https://img.shields.io/badge/github%20actions-%232671E5.svg?style=for-the-badge&logo=githubactions&logoColor=white
[GitHub-Actions-url]: https://github.com/
[Docker]: https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white
[Docker-url]: https://www.docker.com/
[Postgresql.org]: https://img.shields.io/badge/postgres-%23316192.svg?style=for-the-badge&logo=postgresql&logoColor=white
[Postgresql-url]: https://www.docker.com/
