
# Contributing Guide

Thank you for considering contributing to the Time Tool project! This guide provides instructions for setting up your development environment and guidelines for contributing effectively.

## Table of Contents

- [Developer Tools](#developer-tools)
- [Project Setup](#project-setup)
- [Working with Laravel](#working-with-laravel)
- [Working with Hybridly](#working-with-hybridly)
- [Working with Vue.js](#working-with-vuejs)
- [Working with Filament](#working-with-filament)
- [Localization](#localization)
- [Code Style Guidelines](#code-style-guidelines)
- [Commit Message Guidelines](#commit-message-guidelines)
- [Submitting Contributions](#submitting-contributions)

## Developer Tools

Ensure you have the following tools installed:

- **PHP**: Version 8.2 or higher. You can install PHP using the [official installation guide](https://laravel.com/docs/11.x/installation#installing-php).
- **Composer**: Version 2.8 or higher. Installation instructions are available on the [Composer website](https://getcomposer.org/download/).
- **Node.js**: Version 20 or higher. Download it from the [official Node.js website](https://nodejs.org/).
- **npm**: Comes bundled with Node.js.
- **Git**: Version control system. Download it from the [official Git website](https://git-scm.com/downloads).

## Project Setup

1. **Clone the Repository**:

   ```bash
   git clone git@github.com:YournameITManoah/time-tool.git
   cd time-tool
   ```

2. **Install PHP Dependencies**:

   ```bash
   composer install
   ```

3. **Install Node.js Dependencies**:

   ```bash
   npm install
   ```

4. **Set Up Environment Variables**:

   - Duplicate `.env.example` and rename it to `.env`.
   - Configure the necessary environment variables, such as database credentials.

5. **Generate Application Key**:

   ```bash
   php artisan key:generate
   ```

6. **Run Database Migrations**:

   Ensure your database is set up and configured in the `.env` file.

   ```bash
   php artisan migrate
   ```

7. **Start the Development Vite Server**:

   ```bash
   npm run dev
   ```

8. **Start the Development PHP Server**:

   ```bash
   php artisan serve
   ```

   Access the application at `http://127.0.0.1:8000/`.

## Working with Laravel

- **Routing**: Defined in the `routes` directory.
- **Controllers**: Located in `app/Http/Controllers`.
- **Models**: Located in `app/Models`.
- **Migrations**: Located in `database/migrations`.

Refer to the [Laravel documentation](https://laravel.com/docs/11.x) for detailed information.

## Working with Hybridly

Hybridly enhances the integration between Laravel and Vue.js, providing features like partial reloads, forms, dialogs, and more.

- **Installation**: Hybridly is already installed in the project.
- **Configuration**: located in `config/hybridly.php` and `app/Http/Middleware/HandleHybridRequests.php`.
- **Usage**: Refer to the [Hybridly documentation](https://hybridly.dev/guide/) for detailed usage instructions.

## Working with Vue.js

- **Application configuration**: Located in `resources/application`.
- **Components**: Located in `resources/components`.
- **Layouts**: Located in `resources/layouts`.
- **Views**: Located in `resources/views`.
- **State Management**: Managed using Pinia, with store files in `resources/stores`.

Refer to the [Vue.js documentation](https://vuejs.org/guide/) for more information.

## Working with Filament

Filament is used for building the admin panel.

- **Configuration**: Located in `app/providers/Filament/AdminPanelProvider.php`.
- **Resources**: Located in `app/Filament/Resources`.
- **Pages**: Located in `app/Filament/{Resource}/Pages`.

Refer to the [Filament documentation](https://filamentphp.com/docs/) for detailed information.

## Localization

Localization files are located in the `lang` directory. Each language has its own subdirectory containing PHP translation files. These are used by Laravel for validation messages, for example. Each language, except English, also has a json file which contains translations for the frontend of the application and for the frontend of the Filament admin panel.

### New locale

To add a new locale, you need to first copy `lang/en` to `lang/{locale}` and `lang/nl.json` to `lang/{locale}.json`. Then modify the translations strings of the newly created files. When a locale is ready for release you need to define it in three places:

1. `app/SupportedLocale.php`

    ```php
    enum SupportedLocale: string
    {
        case ENGLISH = 'en';
        case DUTCH = 'nl';
        // New locale here
    }
    ```

2. `config/app.php`

    ```php
    'locales' => [
            'en' => 'English',
            'nl' => 'Nederlands',
            // New locale here
        ]
    ```

3. `resources/application/vuetify.ts`

    ```javascript
    import DateFnsAdapter from '@date-io/date-fns'
    import { enUS as enDateFns } from 'date-fns/locale/en-US'
    import { nl as nlDateFns } from 'date-fns/locale/nl'
    // Import date-fns language pack for new locale here

    const vuetify = createVuetify({
        date: {
            adapter: DateFnsAdapter,
            locale: {
                en: enDateFns,
                nl: nlDateFns,
                // New locale here
            },
        },
    })
    ```

Finally, you need to add a flag, representing the language, to the public assets (`public/img/flags/{locale}.svg`). You can retrieve flags from the [Blade Flags repository](https://github.com/MohmmedAshraf/blade-flags/tree/main/resources/svg).

## Code Style Guidelines

- **PHP**: Follow the [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standard.
- **JavaScript**: Follow the recommended JavaScript, TypeScript and Vue rules, enforced by eslint.

Ensure your code is properly formatted before submitting. You can use tools like PHP CS Fixer for PHP and ESLint for JavaScript to automate code formatting.

## Commit Message Guidelines

Commit messages should be completely lowercase and be written in the imperative form (e.g. `add this` or `fix that`).

- **Format**: Use the following structure:

  ```type(scope?): short description```

- **Types**:

  - `feat`: For new features.
  - `fix`: For bug fixes.
  - `refactor`: For code refactoring.
  - `docs`: For documentation updates.
  - `style`: For code style changes.
  - `test`: For adding or updating tests.
  - `chore`: For maintenance that does not change the actual code (e.g. dep updates).
  - `ci`: For changes to the CI workflows.

- **Scopes**

Scopes are not required, but can improve the clarity of the commit message, common scopes include: `deps`, `i18n`, `api`, `auth`, `admin`.

- **Examples**:

  ```bash
  feat(i18n): add Spanish locale
  ```

  ```bash
  fix: validate duplicate time logs
  ```

  ```bash
  chore(deps): update composer packages
  ```

## Submitting Contributions

1. **Fork the Repository**: Click the "Fork" button on the repository's GitHub page.
2. **Create a New Branch**:

   ```bash
   git checkout -b feat/your-feature-name
   ```

3. **Make Changes**: Implement your changes, adhering to the code style guidelines.
4. **Commit Changes**: Write clear and concise commit messages following the commit message guidelines.
5. **Push to Your Fork**: Push your changes to your forked repository.

   ```bash
   git push origin feat/your-feature-name
   ```

6. **Open a Pull Request**: Go to the original repository and open a pull request with a description of your changes.

Thank you for your contributions!
