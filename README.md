## About Project
This is a backend application for "siBunda" app as developed in this thesis ["Pengembangan Aplikasi Healthcare Intelligence System Untuk Pemantauan Kesehatan Ibu Dan Anak : Perancangan Aplikasi Backend"](https://repository.its.ac.id/89802/)

## Getting Started Locally
### Prerequisite
- Docker Engine & Docker Compose

### Serving Up
- Remove `.test` suffix from these file names: `storage/oauth-private.key.test` and `storage/oauth-public.key.test` (Do not use this on production)
- Execute `docker-compose up`. (add `-d` for background run)

## Author Notes
### Area of Improvements
- Upgrade the Framework (and apply non cold-start as in Octane)
- Extract harcoded values to constants or saved in OS ENV/Cache/DB
- Extract branched values/conditions to managable Map constants
- Put Busines Logic in a separate layer e.g: service, usecase
- Let the controllers accept standardized actions/functions
- Implement Role-based middleware
- Add more tests
- And countless improvement to come