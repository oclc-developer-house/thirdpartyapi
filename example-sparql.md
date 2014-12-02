# Example DBPedia SPARQL 

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

## Find Events associated with a particular dat

```
SELECT ?Location WHERE {
 ?Location a dbpedia-owl:Event .
 ?Location dbpedia-owl:date ?date.
FILTER( 
    ( ( datatype(?date) = xsd:date ) || ( datatype(?date) = xsd:dateTime ) ) && 
    ( ?date <= "2014-12-02"^^xsd:dateTime ) && 
    ( regex(str(?date), "[0-9]{4}-12-02") ) 
  )
 }
LIMIT 10
```
