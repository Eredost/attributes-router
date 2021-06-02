# Contributing to Attributes router

First, a big thank you for taking the time to contribute to this project.

The following is a set of guidelines for contributing to Attributes router.
These are mostly guidelines, not rules. Use your best judgement, and feel
free to propose changes to this document in a pull request.

## How to contribute to Attributes router

**Did you find a bug?**

- Do not open up a GitHub issue if the bug relates to a security
  vulnerability, contact us directly
- Ensure the bug was not already reported by searching on GitHub
  under [Issues](https://github.com/Eredost/attributes-router/issues)
- When creating the new issue, be as specific as possible, what are the
  factors that lead to this problem, how to reproduce it, etc

**Did you write a patch that fixes a bug?**

- Open a new GitHub pull request with the patch
- Ensure the PR description clearly describes the problem (if no issues
  were opened) and solution. Include the relevant issue number if applicable.
- The pull request will finally be examined by one or more collaborators of
  the project and will pass a battery of tests with the CodeClimate and Travis tools, if
  these fail, changes will have to be made so that it can be merged

**Do you intend to add a new feature or change an existing one?**

- Suggest your change by creating a new GitHub
  [Issue](https://github.com/Eredost/attributes-router/issues/new) in order to collect
  feedback about the change
- Fork the project repository
- Write your code in a new branch and implement unit and functional tests to
  make sure that there is no regression and that everything works as expected
- Open a new GitHub pull request describing all the new changes brought
- When the pull request has been reviewed by collaborators and no error
  is returned by CodeClimate and Travis, it will be merged.

**Do you have any questions about the source code?**

- Ask any question about Attributes router by contacting us

## Styleguides

### Git commit messages

- Write in English
- Use the present tense ("Add feature" not "Added feature")
- Limit the first line to 72 characters or less
- Start the commit message with the type, here is a non-exhaustive list
  based on the Angular convention:
    - **build**: Changes that affect the build system or external
      dependencies(example scopes: gulp, broccoli, npm)
    - **ci**: Changes to our CI configuration files and scripts
      (example scopes: Circle, BrowserStack, SauceLabs)
    - **docs**: Documentation only changes
    - **feat**: A new feature
    - **fix**: A bug fix
    - **perf**: A code change that improves performance
    - **refactor**: A code change that neither fixes a bug nor adds a feature
    - **test**: Adding missing tests or correcting existing tests

### Branch names

- Write in English
- Limit the branch name to 40 characters
- Name in such a way that it is easily identifiable (example: reverse-routing)

### PHP Styleguide

All written PHP code must comply with PSR [1](https://www.php-fig.org/psr/psr-1)
and [12](https://www.php-fig.org/psr/psr-12/).

The PHP code is linted with the [PHP Mess Detector](https://phpmd.org/)
and [PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer) tools.

### Markdown Styleguide

Documentation written in Markdown files is linted with the
[MarkdownLint](https://github.com/markdownlint/markdownlint) tool.
