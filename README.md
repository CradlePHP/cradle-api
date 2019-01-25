# cradle-api

A developer management and API generator that includes management for the following.
 - **Developer Applications** - Developers can create applications with tokens for rest calls and webhooks
 - **API Scopes** - Admins can group REST calls into scopes which are options that developer apps can use
 - **OAuth2 Server** - Same protocols used in popular websites; developers can enable users to share their information with other applications
 - **REST Generator** - Admins can create standard REST calls without needing to program
 - **Webhook Generator** - Admins can create events that notifies 3rd party apps through developer apps
 - **API Session Management** - Admins can view activity and revoke access manually to any API users on the fly

## Install

If you already installed Cradle, you may not need to install this because it
should be already included.

```
$ composer require cradlephp/cradle-api
$ bin/cradle cradlephp/cradle-api install
$ bin/cradle cradlephp/cradle-api sql-populate
```

## How to Use

By default the `cradle-api` package tables will be empty. You can use this SQL script to manually populate it.

[api.sql](https://github.com/CradlePHP/cradle-api/files/2795216/api.txt)

So when you populate the API schemas a new section `/developer/app/search` will be available. This is like facebook developer portal (but more raw). It also auto creates docs `/developer/docs/scopes` and `/developer/docs/webhooks`.

### REST

There's 3 kinds of rest calls:

 - Public REST call: `/rest/public/profile/search`
 - App REST call: `/rest/public/profile/detail/1?client_id=94341e9d0776b73cc7142cc161faf0e688fdbfb2`
 - User REST call: `/rest/user/app/search?access_token=8cddabc765dbba7cccaa156105af08c04455775c`

You can expect this is following OAuth v2 specs *(as well as Facebook's REST style)* very closely. The following screenshot shows what the form fields are for in `/admin/system/model/rest/create`.

![image](https://user-images.githubusercontent.com/120378/51734253-f652b000-20be-11e9-84dc-b4f89bfb778c.png)

Paths in REST calls can also take route parameters like `/profile/detail/:profile_id` that will also be apart of the event call parameters.

### Web Hooks

Then web hooks are like Github's web hooks.

![image](https://user-images.githubusercontent.com/120378/51734233-e3d87680-20be-11e9-9e3d-51bb4f811fe4.png)

When you create a web hook it will then be available to the application to utilize. But beware the web hook url given should be valid, or else every time you create a profile, it will be slow *(because it's trying to call that web hook url)*.

![image](https://user-images.githubusercontent.com/120378/51734341-331ea700-20bf-11e9-9d45-cb3f23d132df.png)

This way allows to create APIs without needing to program. but, you can also program in your own REST calls and webhooks manually in any `controller.php`. We are not stopping you from doing that.

## OAuth v2

In `/developer/app/search` you can also try the 3-legged OAuth yourself.

![image](https://user-images.githubusercontent.com/120378/51734476-9d374c00-20bf-11e9-8ef7-2e1c0c20a366.png)

This will redirect you to `/dialog/request?client_id=94341e9d0776b73cc7142cc161faf0e688fdbfb2`. You can use this same URL to authenticate via 3-Legged OAuth. For now, just click `Allow`.

![image](https://user-images.githubusercontent.com/120378/51734763-6d3c7880-20c0-11e9-8ea9-23938c7ff9b7.png)

When your done that it will return you back to the same screen with the same URL except with a code parameter. (ie. `/developer/app/search?code=1234567890`). If you have POST MAN you can call `POST /rest/access?client_id=[your app key]&client_secret=[your app secret]&code=[the code you got earlier]`. That will return session tokens in JSON as in the following.

```
POST /rest/access?client_id=94341e9d0776b73cc7142cc161faf0e688fdbfb2&client_secret=d490f575cd1c48e1b970bb0427ae4ec2b2636403&code=b75272cbf7edbb7a434f77e904a27beb4fe08be7

{
    "error": false,
    "results": {
        "access_token": "f7b9427a17ad4f083fb109ba382a99ca",
        "access_secret": "9d5b6d575f13f2c14c5fa8cc843c07fd",
        "profile_id": "1",
        "profile_name": "John Doe",
        "profile_created": "2019-01-20 06:43:42"
    }
}
```

----

<a name="contributing"></a>
# Contributing to Cradle PHP

Thank you for considering to contribute to Cradle PHP.

Please DO NOT create issues in this repository. The official issue tracker is located @ https://github.com/CradlePHP/cradle/issues . Any issues created here will *most likely* be ignored.

Please be aware that master branch contains all edge releases of the current version. Please check the version you are working with and find the corresponding branch. For example `v1.1.1` can be in the `1.1` branch.

Bug fixes will be reviewed as soon as possible. Minor features will also be considered, but give me time to review it and get back to you. Major features will **only** be considered on the `master` branch.

1. Fork the Repository.
2. Fire up your local terminal and switch to the version you would like to
contribute to.
3. Make your changes.
4. Always make sure to sign-off (-s) on all commits made (git commit -s -m "Commit message")

## Making pull requests

1. Please ensure to run [phpunit](https://phpunit.de/) and
[phpcs](https://github.com/squizlabs/PHP_CodeSniffer) before making a pull request.
2. Push your code to your remote forked version.
3. Go back to your forked version on GitHub and submit a pull request.
4. All pull requests will be passed to [Travis CI](https://travis-ci.org/CradlePHP/cradle-api) to be tested. Also note that [Coveralls](https://coveralls.io/github/CradlePHP/cradle-api) is also used to analyze the coverage of your contribution.
