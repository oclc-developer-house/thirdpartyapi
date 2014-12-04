# Example DBPedia SPARQL 

## Exploring properties of unknown

```

PREFIX dbp: <http://dbpedia.org/ontology/>

SELECT DISTINCT ?type 
WHERE {
    ?s rdf:type ?type
    FILTER regex(str(?type), 'http://dbpedia.org/ontology')
}
LIMIT 100
```

## Find Authors born on a particular day

```
PREFIX ont: <http://dbpedia.org/ontology/> 

SELECT DISTINCT ?person ?date  WHERE { 
  ?person ont:birthDate ?date .
  ?person a dbpedia-owl:Writer 
  FILTER( 
    ( ( datatype(?date) = xsd:date ) || ( datatype(?date) = xsd:dateTime ) ) && 
    ( ?date <= "2014-12-02"^^xsd:dateTime ) && 
    ( regex(str(?date), "[0-9]{4}-12-02") ) 
  ) 
}
LIMIT 20
```

## Find Events associated with a particular date

```
SELECT ?event WHERE {
 ?event a dbpedia-owl:Event .
 ?event dbpedia-owl:date ?date.
FILTER( 
    ( ( datatype(?date) = xsd:date ) || ( datatype(?date) = xsd:dateTime ) ) && 
    ( ?date <= "2014-12-02"^^xsd:dateTime ) && 
    ( regex(str(?date), "[0-9]{4}-12-02") ) 
  )
 }
LIMIT 10
```


## Find Countries founded on a particular date

```
PREFIX yago: <http://dbpedia.org/class/yago/>
SELECT * WHERE {
 ?Country a dbpedia-owl:Country.
 ?Country a yago:Country108544813.
 ?Country dbpedia-owl:foundingDate ?date.
FILTER( 
    ( ( datatype(?date) = xsd:date ) || ( datatype(?date) = xsd:dateTime ) ) && 
    ( regex(str(?date), "[0-9]{4}-12-02") ) 
  )
 }
```


## Find Holidays on a particular date

```
PREFIX dbpprop: <http://dbpedia.org/property/>
SELECT * WHERE {
 ?Holiday a dbpedia-owl:Holiday .
 ?Holiday dbpprop:date ?date.
FILTER( 
    ( regex(str(?date), "12-01") && !regex(str(?date), "[0-9]{4}") ) 
  )
 }
```

## Find all the properties of a book
...
PREFIX ont: <http://dbpedia.org/ontology/>
PREFIX prop: <http://dbpedia.org/property/>

SELECT DISTINCT ?prop
WHERE 
{
?s ?prop ?o;
     rdf:type ont:Book
     FILTER regex (str(?prop), 'http://dbpedia.org/property/')

} 
ORDER BY ?prop
...
