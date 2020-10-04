# Data

We've created a basic set of data for you. You can, but don't have to use it. It contains some basic addresses of places where we like to go for lunch and our office.

## Import original data

In case you want to develop against a local database you can import basic set of data via

```
$ mongoimport -h $HOST:$PORT -d $DATABASE -c addresses -u $USER -p $PASSWORD --file data/addresses.json --type json --jsonArray
```

