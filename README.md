# Joomla! X - The Lighthouse Project

[![Build Status](https://travis-ci.org/joomla-projects/joomla-pythagoras.svg?branch=master)](https://travis-ci.org/joomla-projects/joomla-pythagoras)

This branch contains the current development of Joomla! X (formerly known as Joomla! 4),
based on a clean-base approach.

## Why Clean Base?

We honestly tried an incremental approach, i.e., to transform J!3 into J!4 using small steps.
The tight coupling and non-sufficient test coverage, however, makes it extremely hard to apply a state-of-the-art architecture.

It was suggested to start from a fresh ground, the clean base, to create the basic architecture,
and to port existing functionality in a second step.
The J!X Architecture Team decided to try this approach.

## Why Lighthouse?

The idea behind Joomla! X is not to replace the current product, but to define the development goals, just like a lighthouse shows ships where to head. Both projects, current Joomla! and Joomla! X, will work towards each other, until we can switch with a one-click update. 

## The "Chris Davenport Happiness Milestone" (CDHM)

The team defined a milestone, named after the team member, Chris Davenport, who brought up the requirements.
You can see the original document here: [docs/j4cdhms.md](docs/j4cdhms.md).

The final decision is made, when the milestone is reached, or it shows out, that it can not be reached with the clean base approach.

  - [How to test the Workflow Horizontal Component](docs/workflow.md)

## Installation

In order to run Joomla! X in the current state, you should have

  - `composer` (required) and
  - `docker-compose` (recommended)
  
installed and working on your system.

Check out the `master` branch and run

```bash
$ composer install
$ ./install.sh
```

You can check, which CLI commands are available with

```bash
$ ./joomla list
```

To see the rendered output of Joomla! X in the browser, it is recommended to use the pre-configured docker environment.

> If you don't want to use the docker setup, please make sure, that your webserver can access the project directory 
_as document root_. It depends on your setup, how the demo is accessed - normally, it should be just `localhost`.
The current installer is very basic, and can not yet cater for sub-directories, so if your document root does not
match the project root, images, JavaScript, and CSS can probably not be loaded.

The docker environment will be used for system and acceptance tests, so you need it anyway for development.
Of course, the final version will run without these development specific tools.

```bash
$ docker-compose up -d
```

Navigate your browser to `localhost:8080`.

You can stop the containers with

```bash
$ docker-compose stop
```

## Contribute

Joomla! X is a big endeavour, and it surely needs some more hands on it.

  - Add issues to the tracker to discuss things.
  - Fork this repo, write code, and send a PR against this branch.
    Before sending your PR, please check your contribution first using
    
    ```bash
    $ ./libraries/vendor/bin/robo test
    $ ./libraries/vendor/bin/robo check:style
    ```
    
Thank you!

*The Joomla! X Architecture Team*
