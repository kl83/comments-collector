# Feedbacks grabber

Console script to grab feedbacks.

Available sources:
- Google
- Booking
- Yandex
- Tripadvisor

Supported storages:
- WordPress

## Installation

```
composer create-project kl83/comments-collector
```

## Configuration

Create and edit config.php file.
See configuration example in config.dist.php file.

```
cp config.dist.php config.php
nano config.php
```

## Usage

Run the comcol and feedbacks will be grabbed from the
configured sources to configured storage.

```
./comcol
```

## License

MIT