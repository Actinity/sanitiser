A simple class to fix common input errors in Email addresses.

This library takes a string and tries to turn it into a 'clean' email address without any extraneous junk that would cause it to fail.

### Why would I use it?

This library was written to simplify importing email addresses from files and other
systems where proper sanitisation hadn't previously been applied. Lists of users
to add to a system supplied in Excel, email addresses collected from conferences
etc. The intention is to be 'good enough' to fix 99% of the common errors you see
in that kind of data, leaving you free to only look at the ones that definitely
failed.

### What does it do?

Catch and 'fix' a variety of common issues like:

- Trailing semi-colons, commas etc
- Leading or trailing whitespace (including zero-widths)
- Addresses wrapped in quotes or primes
- Addresses wrapped in angle brackets (discarding anything else like a name)
- Multiple email addresses in the same string (only the first will be kept)
- Rejects obviously broken addresses like `test`, `test@`, `test@.` etc.

### What it doesn't do

- Verify that the email address/domain/mail server exists or will work, obviously. The only way to test an email address is to actually send to it. Anyone who tells you otherwise is selling something.
- Work for every possible RFC compliant email - there are plenty of things you're technically allowed to do that no-one in the real-world actually does.
- Guarantee that it matches the user's intent (e.g. if there were multiple email addresses listed, it will only pick the first one. Is that correct? ¯\\_(ツ)_/¯)


### How to use it.

`use Actinity\Sanitiser\Sanitiser;`

`$address = Sanitiser::clean($address)`

`clean()` returns either a clean address, or false if it looks hopelessly broken.