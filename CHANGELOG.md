# Changelog

## v0.0.2

### Added or Changed

Added README, License and EULA

### Removed

- Nothing

## v0.0.1

### Added or Changed

Added Branching Strategy
 GitFlow Branching Model

This project follows the **GitFlow branching strategy**.  
The goal is to keep `main` always production-ready while using `dev` as an integration branch.  
All work happens in short-lived branches that are deleted after merge. 

Core branches:
1. `main` (or `master`) → always production-ready, deployed code.
2. `dev` (or `develop`) → integration branch where features and fixes are merged before going to main.

Short-lived branches (temporary branches, deleted after merge):
  - `feature/<name>` → for new functionality. Created from `dev`, merged back into `dev`.
  - `bugfix/<name>` → for fixing bugs. Created from `dev`, merged back into `dev`.
  - `release/<version>` → staging branch to prepare a version before tagging and merging to `main`. created from `dev`, merged into both `main` and `dev`.
  - `hotfix/<name>` → for urgent production fixes (branched off `main`, merged back to both `main` and `dev`).
