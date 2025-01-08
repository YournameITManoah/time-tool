# time-tool

This is the repository for the time registration tool created by Manoah Tervoort.

## Purpose

This application was developed as part of the graduation project of Manoah Tervoort, for his Information Technology Study at InHolland Haarlem.

## Contributing

In order to contribute and understand the code, please see the [contributing guide](./CONTRIBUTING.md).

## Application

The application consists of a user panel and admin panel.

### User Panel

The user panel is available at the root of the application. It allows users to manage their own time logs.

### Admin Panel

The admin panel is available at `/admin`. It allows administrators to manage clients, projects, tasks and users.

### Localization

By default, the application will try to find a supported locale in the `Accept-Language` header. The locale can be overwritten from both the user panel and the admin panel and is synced between the two.

## API

This application has an API that can be talked to from external applications.

### Connection

The following steps are required to be able to connect to the API:

1. **Receive an API token**: You need an API token in order to establish a connection to the API. This API token is used to request a User Access Token in a later step.

2. **Start a session**: Start a session with the API, by calling the `/api/csrf-cookie` endpoint. This endpoint will start a session and create a `XSRF-TOKEN` cookie.

3. **add X-XSRF_TOKEN header**: Include the `X-XSRF_TOKEN` header to every other request with the value of the `XSRF-TOKEN` cookie.

4. **Request User Access Token**: Request a user-specific access token by doing a POST request on the `/api/auth/token` endpoint. Provide the API token and the email address of the user you wish to perform actions for.

5. **Add Authorization header**: Include the User Access Token as a Bearer Token to every request.

### API Localization

The API uses the `Accept-Language` header to determine what language the API responses should be in.

### Documentation

All API endpoints are documented at `/api/`.
