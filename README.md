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
    This project is a vehicle selling platform designed to connect buyers and sellers through a user-friendly web application. It allows dealerships and individual sellers to list vehicles with detailed specifications, images, and pricing, while providing buyers with powerful search and filtering tools to find the right vehicle. The application supports account management, inventory tracking, and secure communication between buyers and sellers, ensuring a streamlined and efficient vehicle marketplace experience.
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

Here's a blank template to get started. 

<p align="right">(<a href="#readme-top">back to top</a>)</p>



### Built With

* [![Laravel][Laravel.com]][Laravel-url]
* [![AlpineJS][Alpine.js]][Alpine.js-url]
* [![Python][Python.org]][Python.org-url]

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- GETTING STARTED -->
## Getting Started

This is an example of how you may give instructions on setting up your project locally.
To get a local copy up and running follow these simple example steps.

### Running The Seeders

- **For local development:** Just run the standard command. This will execute DatabaseSeeder, which in turn runs your DevelopmentSeeder.

```sh
# Seed the data
php artisan migrate:fresh --seed
```

- **For production deployment:** Explicitly specify the ProductionSeeder in your deployment script. This ensures no fake data ever touches your live database. The `--force` flag is required to run seeders in a production environment.

```sh
php artisan db:seed --class=ProductionSeeder --force
```

- **For testing or specific tasks:** You can run any individual seeder you need.

```sh
# Just refresh the locations
php artisan db:seed --class=LocationSeeder

# Or refresh only the demo data after a migration
php artisan migrate:fresh --seed --seeder=DemoDataSeeder
```

### How to Start The App Locally

- In the first terminal run
```sh
# Start the PHP server
php artisan serve
```

- In a second terminal run
```sh
# Start the Vite dev server
npm run dev
```

- In a third terminal run
```sh
# Start the queue worker
php artisan queue:work
```

### Prerequisites

#### Prerequisites At OS Level

  ```sh
  # Install PHP GD and set in .ini file
  sudo apt update && sudo apt install php-gd

  # Or Install ImageMagick
  # In config/image.php set driver to imagemagick
  sudo apt update && sudo apt install imagemagick

  # Install Required CLI Tools To Support WebP
  sudo apt install jpegoptim optipng pngquant gifsicle svgo webp
  
  ```

#### Prerequisites At Application Level

  ```sh
  # Create Storage Link
  php artisan storage:link

  # Install Intervention Image
  # composer require intervention/image

  # Install Spatie Image Optimizer
  # composer require spatie/laravel-image-optimizer

  # Publish Spatie Config
  # php artisan vendor:publish --provider="Spatie\LaravelImageOptimizer\ImageOptimizerServiceProvider" --tag="config"
  
  ```

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
- [ ] Refactor Vehicles to Listings
  - [ ] Refactor Controller
  - [ ] Refactor Routes
  - [ ] Refactor Migrations
  - [ ] Refactor Seeders
  - [ ] Refactor Factories
  - [ ] Refactor Views
  - [ ] Refactor View Components
  - [ ] Refactor Models
  - [ ] Refactor JS
  - [ ] Refactor Jobs
  - [ ] Refactor Policies
  - [ ] Refactor Services
  - [ ] Refactor `config/vehicles` to `config/categories`
- [ ] Change Features Table From Wide Table vs. Narrow Table
  - [ ] Update `features` Table
  - [ ] Create `feature_vehicle` Pivot Table
  - [ ] Update `Feature` Model
  - [ ] Update `Vehicle` Model
  - [ ] Update Feature Factory
  - [ ] Create Config With Default Data
- [ ] Category Aware Create Form
  - [ ] Create BreadCrumb Component
- [ ] Year Manufacturer Model API
    - [ ] Normalize `vehicle_features` table into a proper many-to-many relationship
      - [ ] Create New Tables
        - [ ] `create_features_table`
        - [ ] `create_feature_listing_table`
      - [ ] Create a seeder to move data to the new structure
      - [ ] `make:migration drop_vehicle_features_table`
    - [ ] Create `variants` table to define a specific vehicle type
      - [ ] Create the `variants` Table
      - [ ] Populate the `variants` Table
    - [ ] Rename `vehicles` table to `listings` to represent an actual item for sale
      - [ ] Modify and Rename the Table
    - [ ] Final Step: Update Your Eloquent Models

See the [open issues](https://github.com/monatemedia/dealership/issues) for a full list of proposed features (and known issues).

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- CONTRIBUTING -->
## Contributing

We use the `GitFlow Branching Model`. To make a contribution, please fork the repo and create a pull request. You can also <a href="https://github.com/monatemedia/dealership/issues/new?labels=bug&template=bug-report---.md">report a bug</a>, or <a href="https://github.com/monatemedia/dealership/issues/new?labels=enhancement&template=feature-request---.md">request a feature</a>.

### GitFlow Branching Model

This project follows the **GitFlow branching strategy**.  

The goal is to keep `main` always production-ready while using `dev` as an integration branch.  
All work happens in short-lived branches that are deleted after merge. 

Core branches:
1. `main` → always production-ready, deployed code.
2. `dev`  → integration branch where features and fixes are merged before going to main.

Short-lived branches (temporary branches, deleted after merge):
  - `feature/<name>` → for new functionality. Created from `dev`, merged back into `dev`.
  - `bugfix/<name>` → for fixing bugs. Created from `dev`, merged back into `dev`.
  - `release/<version>` → staging branch to prepare a version before tagging and merging to `main`. created from `dev`, merged into both `main` and `dev`.
  - `hotfix/<name>` → for urgent production fixes (branched off `main`, merged back to both `main` and `dev`).


---

### Core Branches
1. **`main`**  
   - Always production-ready.  
   - Code here is what’s deployed.  

2. **`dev`**
   - Integration branch.  
   - Features and bugfixes merge here before going to `main`.  

---

### Short-Lived Branches

#### Feature Branches
- For new functionality.  
- Created from `dev`, merged back into `dev`.  

```bash
# CREATE A FEATURE BRANCH
# ---------------------

# Make sure you're on dev
git checkout dev

# Update dev with latest remote
git pull origin dev

# Create the new feature branch
git checkout -b feature/<name>

# work, commit
git push origin feature/<name>

# MERGE FEATURE BRANCH
# --------------------

# List all branches that contain the tip commit of your feature branch
git branch --contains feature/<name>

# Make sure all your work is committed on the feature branch
git status
git add .
git commit -m "Meaningful Message"   # if needed

# Push the feature branch to remote (first time)
git push -u origin feature/<name>

# make sure you're on dev
git checkout dev

# Update dev with latest remote
git pull origin dev

# Merge the feature branch
git merge feature/<name>

# Push the merged result
git push origin dev

# List all branches that contain the tip commit of your feature branch
git branch --contains feature/<name>

# View recent history on dev, check for your commits
git log --oneline --graph --decorate -20

# Delete the local feature branch 
# Use -D to force if branch isn't merged
git branch -d feature/<name>

# Delete the remote feature branch
git push origin --delete feature/<name>
```

Merge via Pull Request into `dev`.

---

#### Bugfix Branches

* For fixing bugs (not urgent production issues).
* Created from `dev`, merged back into `dev`.

```bash
git checkout dev
git pull origin dev
git checkout -b bugfix/<name>
# work, commit
git push origin bugfix/<name>
```

Merge via Pull Request into `dev`.

---

#### Release Branches

* For preparing a version before tagging and merging into production.
* Created from `dev`, merged into both `main` and `dev`.

```bash
git checkout dev
git pull origin dev
git checkout -b release/<version>
# final tweaks, version bumps
git push origin release/<version>
```

Merge via Pull Request into both `main` and `dev`.
Tag the release on `main`:

```bash
git checkout main
git pull origin main
git tag -a v<version> -m "Release v<version>"
git push origin v<version>
```

---

#### Hotfix Branches

* For urgent production fixes.
* Created from `main`, merged back into both `main` and `dev`.

```bash
git checkout main
git pull origin main
git checkout -b hotfix/<name>
# work, commit
git push origin hotfix/<name>
```

Merge via Pull Request into both `main` and `dev`.
Tag the hotfix on `main`:

```bash
git tag -a v<version+patch> -m "Hotfix v<version+patch>"
git push origin v<version+patch>
```

---

### Standard Workflow

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

---

### Summary of Branch Sources

* `feature/*` → from `dev`, merge into `dev`.
* `bugfix/*` → from `dev`, merge into `dev`.
* `release/*` → from `dev`, merge into `main` + `dev`.
* `hotfix/*` → from `main`, merge into `main` + `dev`.


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
[Alpine.org-url]: https://alpinejs.dev/
[Python.org]: https://img.shields.io/badge/python-3670A0?style=for-the-badge&logo=python&logoColor=ffdd54
[Python.org-url]: https://www.python.org/
