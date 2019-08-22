On a step when we uploading new file for import, we have to define it's format. 

This format is different for each file type. For example **.csv* have different parsing options as **.xml*.

And here currenty we have solution that have to be made.

Format option is unique per each file type, so we have 2 ways ti implement it in DTO:

## 1) Make $format Interface general for all file types, by defining

```
/**
 * Get Data
 *
 * @return string[]
*/
public function getData(): array;
```

URL will look like: `POST {{URL}}rest/V1/imports/sources`

**PROS**:
* We can add new file types without creating new DTO interfaces, and each file type could contain own set of options to process.

**CONS**:
* We are not defining strict type and interface for this objects, which lead to the situation that developers can put any parameters in Format Data
* As are not defining strict type, Swagger will not show to us available options for file upload. We as developers or integrators have to know them, and they have to be documented.


## 2) Make $format specific for each file type.

CsvSourceFormatInterface $format vs SourceFormatInterface $format.

**PROS** :
* We have a strict types of each possible format defined on DTO level, so input request always will be validated against DTO.
* Swagger will deliver all paramethers that are availabe for each import type

**CONS**: 
a) Each file type upload will require to have: 

* own endpoint to upload
* own DTOs for upload, update, get methods

This will lead us to increasing of complexity when we want to add new file type for processing

URLs will look like: 

`POST {{URL}}rest/V1/imports/sources/csv`

`POST {{URL}}rest/V1/imports/sources/xml`
...