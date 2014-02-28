<?php

/**
 * 
 *
 * @version 1.105
 * @package entity
 */
class GCProjektModel extends Db2PhpEntityBase implements Db2PhpEntityModificationTracking {
	private static $CLASS_NAME='GCProjektModel';
	const SQL_IDENTIFIER_QUOTE='`';
	const SQL_TABLE_NAME='c_projekt';
	const SQL_INSERT='INSERT INTO `c_projekt` (`id_c_projekt`,`razeni`,`kod`,`text`,`plny_text`,`valid`) VALUES (?,?,?,?,?,?)';
	const SQL_INSERT_AUTOINCREMENT='INSERT INTO `c_projekt` (`razeni`,`kod`,`text`,`plny_text`,`valid`) VALUES (?,?,?,?,?)';
	const SQL_UPDATE='UPDATE `c_projekt` SET `id_c_projekt`=?,`razeni`=?,`kod`=?,`text`=?,`plny_text`=?,`valid`=? WHERE `id_c_projekt`=?';
	const SQL_SELECT_PK='SELECT * FROM `c_projekt` WHERE `id_c_projekt`=?';
	const SQL_DELETE_PK='DELETE FROM `c_projekt` WHERE `id_c_projekt`=?';
	const FIELD_ID_C_PROJEKT=-887780566;
	const FIELD_RAZENI=1292004526;
	const FIELD_KOD=-316127673;
	const FIELD_TEXT=-1209764026;
	const FIELD_PLNY_TEXT=-1580037332;
	const FIELD_VALID=1153736963;
	private static $PRIMARY_KEYS=array(self::FIELD_ID_C_PROJEKT);
	private static $AUTOINCREMENT_FIELDS=array(self::FIELD_ID_C_PROJEKT);
	private static $FIELD_NAMES=array(
		self::FIELD_ID_C_PROJEKT=>'id_c_projekt',
		self::FIELD_RAZENI=>'razeni',
		self::FIELD_KOD=>'kod',
		self::FIELD_TEXT=>'text',
		self::FIELD_PLNY_TEXT=>'plny_text',
		self::FIELD_VALID=>'valid');
	private static $PROPERTY_NAMES=array(
		self::FIELD_ID_C_PROJEKT=>'idCProjekt',
		self::FIELD_RAZENI=>'razeni',
		self::FIELD_KOD=>'kod',
		self::FIELD_TEXT=>'text',
		self::FIELD_PLNY_TEXT=>'plnyText',
		self::FIELD_VALID=>'valid');
	private static $PROPERTY_TYPES=array(
		self::FIELD_ID_C_PROJEKT=>Db2PhpEntity::PHP_TYPE_INT,
		self::FIELD_RAZENI=>Db2PhpEntity::PHP_TYPE_INT,
		self::FIELD_KOD=>Db2PhpEntity::PHP_TYPE_STRING,
		self::FIELD_TEXT=>Db2PhpEntity::PHP_TYPE_STRING,
		self::FIELD_PLNY_TEXT=>Db2PhpEntity::PHP_TYPE_STRING,
		self::FIELD_VALID=>Db2PhpEntity::PHP_TYPE_BOOL);
	private static $FIELD_TYPES=array(
		self::FIELD_ID_C_PROJEKT=>array(Db2PhpEntity::JDBC_TYPE_INTEGER,10,0,false),
		self::FIELD_RAZENI=>array(Db2PhpEntity::JDBC_TYPE_INTEGER,10,0,false),
		self::FIELD_KOD=>array(Db2PhpEntity::JDBC_TYPE_VARCHAR,20,0,false),
		self::FIELD_TEXT=>array(Db2PhpEntity::JDBC_TYPE_VARCHAR,200,0,true),
		self::FIELD_PLNY_TEXT=>array(Db2PhpEntity::JDBC_TYPE_VARCHAR,500,0,true),
		self::FIELD_VALID=>array(Db2PhpEntity::JDBC_TYPE_BIT,0,0,true));
	private static $DEFAULT_VALUES=array(
		self::FIELD_ID_C_PROJEKT=>null,
		self::FIELD_RAZENI=>0,
		self::FIELD_KOD=>'""',
		self::FIELD_TEXT=>null,
		self::FIELD_PLNY_TEXT=>null,
		self::FIELD_VALID=>'1');
	private $idCProjekt;
	private $razeni;
	private $kod;
	private $text;
	private $plnyText;
	private $valid;

	/**
	 * set value for id_c_projekt 
	 *
	 * type:INT UNSIGNED,size:10,default:null,primary,unique,autoincrement
	 *
	 * @param mixed $idCProjekt
	 * @return GCProjektModel
	 */
	public function &setIdCProjekt($idCProjekt) {
		$this->notifyChanged(self::FIELD_ID_C_PROJEKT,$this->idCProjekt,$idCProjekt);
		$this->idCProjekt=$idCProjekt;
		return $this;
	}

	/**
	 * get value for id_c_projekt 
	 *
	 * type:INT UNSIGNED,size:10,default:null,primary,unique,autoincrement
	 *
	 * @return mixed
	 */
	public function getIdCProjekt() {
		return $this->idCProjekt;
	}

	/**
	 * set value for razeni 
	 *
	 * type:INT,size:10,default:null
	 *
	 * @param mixed $razeni
	 * @return GCProjektModel
	 */
	public function &setRazeni($razeni) {
		$this->notifyChanged(self::FIELD_RAZENI,$this->razeni,$razeni);
		$this->razeni=$razeni;
		return $this;
	}

	/**
	 * get value for razeni 
	 *
	 * type:INT,size:10,default:null
	 *
	 * @return mixed
	 */
	public function getRazeni() {
		return $this->razeni;
	}

	/**
	 * set value for kod 
	 *
	 * type:VARCHAR,size:20,default:""
	 *
	 * @param mixed $kod
	 * @return GCProjektModel
	 */
	public function &setKod($kod) {
		$this->notifyChanged(self::FIELD_KOD,$this->kod,$kod);
		$this->kod=$kod;
		return $this;
	}

	/**
	 * get value for kod 
	 *
	 * type:VARCHAR,size:20,default:""
	 *
	 * @return mixed
	 */
	public function getKod() {
		return $this->kod;
	}

	/**
	 * set value for text 
	 *
	 * type:VARCHAR,size:200,default:null,nullable
	 *
	 * @param mixed $text
	 * @return GCProjektModel
	 */
	public function &setText($text) {
		$this->notifyChanged(self::FIELD_TEXT,$this->text,$text);
		$this->text=$text;
		return $this;
	}

	/**
	 * get value for text 
	 *
	 * type:VARCHAR,size:200,default:null,nullable
	 *
	 * @return mixed
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * set value for plny_text 
	 *
	 * type:VARCHAR,size:500,default:null,nullable
	 *
	 * @param mixed $plnyText
	 * @return GCProjektModel
	 */
	public function &setPlnyText($plnyText) {
		$this->notifyChanged(self::FIELD_PLNY_TEXT,$this->plnyText,$plnyText);
		$this->plnyText=$plnyText;
		return $this;
	}

	/**
	 * get value for plny_text 
	 *
	 * type:VARCHAR,size:500,default:null,nullable
	 *
	 * @return mixed
	 */
	public function getPlnyText() {
		return $this->plnyText;
	}

	/**
	 * set value for valid 
	 *
	 * type:BIT,size:0,default:1,nullable
	 *
	 * @param mixed $valid
	 * @return GCProjektModel
	 */
	public function &setValid($valid) {
		$this->notifyChanged(self::FIELD_VALID,$this->valid,$valid);
		$this->valid=$valid;
		return $this;
	}

	/**
	 * get value for valid 
	 *
	 * type:BIT,size:0,default:1,nullable
	 *
	 * @return mixed
	 */
	public function getValid() {
		return $this->valid;
	}

	/**
	 * Get table name
	 *
	 * @return string
	 */
	public static function getTableName() {
		return self::SQL_TABLE_NAME;
	}

	/**
	 * Get array with field id as index and field name as value
	 *
	 * @return array
	 */
	public static function getFieldNames() {
		return self::$FIELD_NAMES;
	}

	/**
	 * Get array with field id as index and property name as value
	 *
	 * @return array
	 */
	public static function getPropertyNames() {
		return self::$PROPERTY_NAMES;
	}

	/**
	 * get the field name for the passed field id.
	 *
	 * @param int $fieldId
	 * @param bool $fullyQualifiedName true if field name should be qualified by table name
	 * @return string field name for the passed field id, null if the field doesn't exist
	 */
	public static function getFieldNameByFieldId($fieldId, $fullyQualifiedName=true) {
		if (!array_key_exists($fieldId, self::$FIELD_NAMES)) {
			return null;
		}
		$fieldName=self::SQL_IDENTIFIER_QUOTE . self::$FIELD_NAMES[$fieldId] . self::SQL_IDENTIFIER_QUOTE;
		if ($fullyQualifiedName) {
			return self::SQL_IDENTIFIER_QUOTE . self::SQL_TABLE_NAME . self::SQL_IDENTIFIER_QUOTE . '.' . $fieldName;
		}
		return $fieldName;
	}

	/**
	 * Get array with field ids of identifiers
	 *
	 * @return array
	 */
	public static function getIdentifierFields() {
		return self::$PRIMARY_KEYS;
	}

	/**
	 * Get array with field ids of autoincrement fields
	 *
	 * @return array
	 */
	public static function getAutoincrementFields() {
		return self::$AUTOINCREMENT_FIELDS;
	}

	/**
	 * Get array with field id as index and property type as value
	 *
	 * @return array
	 */
	public static function getPropertyTypes() {
		return self::$PROPERTY_TYPES;
	}

	/**
	 * Get array with field id as index and field type as value
	 *
	 * @return array
	 */
	public static function getFieldTypes() {
		return self::$FIELD_TYPES;
	}

	/**
	 * Assign default values according to table
	 * 
	 */
	public function assignDefaultValues() {
		$this->assignByArray(self::$DEFAULT_VALUES);
	}


	/**
	 * return hash with the field name as index and the field value as value.
	 *
	 * @return array
	 */
	public function toHash() {
		$array=$this->toArray();
		$hash=array();
		foreach ($array as $fieldId=>$value) {
			$hash[self::$FIELD_NAMES[$fieldId]]=$value;
		}
		return $hash;
	}

	/**
	 * return array with the field id as index and the field value as value.
	 *
	 * @return array
	 */
	public function toArray() {
		return array(
			self::FIELD_ID_C_PROJEKT=>$this->getIdCProjekt(),
			self::FIELD_RAZENI=>$this->getRazeni(),
			self::FIELD_KOD=>$this->getKod(),
			self::FIELD_TEXT=>$this->getText(),
			self::FIELD_PLNY_TEXT=>$this->getPlnyText(),
			self::FIELD_VALID=>$this->getValid());
	}


	/**
	 * return array with the field id as index and the field value as value for the identifier fields.
	 *
	 * @return array
	 */
	public function getPrimaryKeyValues() {
		return array(
			self::FIELD_ID_C_PROJEKT=>$this->getIdCProjekt());
	}

	/**
	 * cached statements
	 *
	 * @var array<string,array<string,PDOStatement>>
	 */
	private static $stmts=array();
	private static $cacheStatements=true;
	
	/**
	 * prepare passed string as statement or return cached if enabled and available
	 *
	 * @param PDO $db
	 * @param string $statement
	 * @return PDOStatement
	 */
	protected static function prepareStatement(PDO $db, $statement) {
		if(self::isCacheStatements()) {
			if (in_array($statement, array(self::SQL_INSERT, self::SQL_INSERT_AUTOINCREMENT, self::SQL_UPDATE, self::SQL_SELECT_PK, self::SQL_DELETE_PK))) {
				$dbInstanceId=spl_object_hash($db);
				if (null===self::$stmts[$statement][$dbInstanceId]) {
					self::$stmts[$statement][$dbInstanceId]=$db->prepare($statement);
				}
				return self::$stmts[$statement][$dbInstanceId];
			}
		}
		return $db->prepare($statement);
	}

	/**
	 * Enable statement cache
	 *
	 * @param bool $cache
	 */
	public static function setCacheStatements($cache) {
		self::$cacheStatements=true==$cache;
	}

	/**
	 * Check if statement cache is enabled
	 *
	 * @return bool
	 */
	public static function isCacheStatements() {
		return self::$cacheStatements;
	}

	/**
	 * Query by Example.
	 *
	 * Match by attributes of passed example instance and return matched rows as an array of GCProjektModel instances
	 *
	 * @param PDO $db a PDO Database instance
	 * @param GCProjektModel $example an example instance defining the conditions. All non-null properties will be considered a constraint, null values will be ignored.
	 * @param boolean $and true if conditions should be and'ed, false if they should be or'ed
	 * @param array $sort array of DSC instances
	 * @return GCProjektModel[]
	 */
	public static function findByExample(PDO $db,GCProjektModel $example, $and=true, $sort=null) {
		$exampleValues=$example->toArray();
		$filter=array();
		foreach ($exampleValues as $fieldId=>$value) {
			if (null!==$value) {
				$filter[$fieldId]=$value;
			}
		}
		return self::findByFilter($db, $filter, $and, $sort);
	}

	/**
	 * Query by filter.
	 *
	 * The filter can be either an hash with the field id as index and the value as filter value,
	 * or a array of DFC instances.
	 *
	 * Will return matched rows as an array of GCProjektModel instances.
	 *
	 * @param PDO $db a PDO Database instance
	 * @param array $filter array of DFC instances defining the conditions
	 * @param boolean $and true if conditions should be and'ed, false if they should be or'ed
	 * @param array $sort array of DSC instances
	 * @return GCProjektModel[]
	 */
	public static function findByFilter(PDO $db, $filter, $and=true, $sort=null) {
		if (!($filter instanceof DFCInterface)) {
			$filter=new DFCAggregate($filter, $and);
		}
		$sql='SELECT * FROM `c_projekt`'
		. self::buildSqlWhere($filter, $and, false, true)
		. self::buildSqlOrderBy($sort);

		$stmt=self::prepareStatement($db, $sql);
		self::bindValuesForFilter($stmt, $filter);
		return self::fromStatement($stmt);
	}

	/**
	 * Will execute the passed statement and return the result as an array of GCProjektModel instances
	 *
	 * @param PDOStatement $stmt
	 * @return GCProjektModel[]
	 */
	public static function fromStatement(PDOStatement $stmt) {
		$affected=$stmt->execute();
		if (false===$affected) {
			$stmt->closeCursor();
			throw new Exception($stmt->errorCode() . ':' . var_export($stmt->errorInfo(), true), 0);
		}
		return self::fromExecutedStatement($stmt);
	}

	/**
	 * returns the result as an array of GCProjektModel instances without executing the passed statement
	 *
	 * @param PDOStatement $stmt
	 * @return GCProjektModel[]
	 */
	public static function fromExecutedStatement(PDOStatement $stmt) {
		$resultInstances=array();
		while($result=$stmt->fetch(PDO::FETCH_ASSOC)) {
			$o=new GCProjektModel();
			$o->assignByHash($result);
			$o->notifyPristine();
			$resultInstances[]=$o;
		}
		$stmt->closeCursor();
		return $resultInstances;
	}

	/**
	 * Get sql WHERE part from filter.
	 *
	 * @param array $filter
	 * @param bool $and
	 * @param bool $fullyQualifiedNames true if field names should be qualified by table name
	 * @param bool $prependWhere true if WHERE should be prepended to conditions
	 * @return string
	 */
	public static function buildSqlWhere($filter, $and, $fullyQualifiedNames=true, $prependWhere=false) {
		if (!($filter instanceof DFCInterface)) {
			$filter=new DFCAggregate($filter, $and);
		}
		return $filter->buildSqlWhere(new self::$CLASS_NAME, $fullyQualifiedNames, $prependWhere);
	}

	/**
	 * get sql ORDER BY part from DSCs
	 *
	 * @param array $sort array of DSC instances
	 * @return string
	 */
	protected static function buildSqlOrderBy($sort) {
		return DSC::buildSqlOrderBy(new self::$CLASS_NAME, $sort);
	}

	/**
	 * bind values from filter to statement
	 *
	 * @param PDOStatement $stmt
	 * @param DFCInterface $filter
	 */
	public static function bindValuesForFilter(PDOStatement &$stmt, DFCInterface $filter) {
		$filter->bindValuesForFilter(new self::$CLASS_NAME, $stmt);
	}

	/**
	 * Execute select query and return matched rows as an array of GCProjektModel instances.
	 *
	 * The query should of course be on the table for this entity class and return all fields.
	 *
	 * @param PDO $db a PDO Database instance
	 * @param string $sql
	 * @return GCProjektModel[]
	 */
	public static function findBySql(PDO $db, $sql) {
		$stmt=$db->query($sql);
		return self::fromExecutedStatement($stmt);
	}

	/**
	 * Delete rows matching the filter
	 *
	 * The filter can be either an hash with the field id as index and the value as filter value,
	 * or a array of DFC instances.
	 *
	 * @param PDO $db
	 * @param array $filter
	 * @param bool $and
	 * @return mixed
	 */
	public static function deleteByFilter(PDO $db, $filter, $and=true) {
		if (!($filter instanceof DFCInterface)) {
			$filter=new DFCAggregate($filter, $and);
		}
		if (0==count($filter)) {
			throw new InvalidArgumentException('refusing to delete without filter'); // just comment out this line if you are brave
		}
		$sql='DELETE FROM `c_projekt`'
		. self::buildSqlWhere($filter, $and, false, true);
		$stmt=self::prepareStatement($db, $sql);
		self::bindValuesForFilter($stmt, $filter);
		$affected=$stmt->execute();
		if (false===$affected) {
			$stmt->closeCursor();
			throw new Exception($stmt->errorCode() . ':' . var_export($stmt->errorInfo(), true), 0);
		}
		$stmt->closeCursor();
		return $affected;
	}

	/**
	 * Assign values from array with the field id as index and the value as value
	 *
	 * @param array $array
	 */
	public function assignByArray($array) {
		$result=array();
		foreach ($array as $fieldId=>$value) {
			$result[self::$FIELD_NAMES[$fieldId]]=$value;
		}
		$this->assignByHash($result);
	}

	/**
	 * Assign values from hash where the indexes match the tables field names
	 *
	 * @param array $result
	 */
	public function assignByHash($result) {
		$this->setIdCProjekt($result['id_c_projekt']);
		$this->setRazeni($result['razeni']);
		$this->setKod($result['kod']);
		$this->setText($result['text']);
		$this->setPlnyText($result['plny_text']);
		$this->setValid($result['valid']);
	}

	/**
	 * Get element instance by it's primary key(s).
	 * Will return null if no row was matched.
	 *
	 * @param PDO $db
	 * @return GCProjektModel
	 */
	public static function findById(PDO $db,$idCProjekt) {
		$stmt=self::prepareStatement($db,self::SQL_SELECT_PK);
		$stmt->bindValue(1,$idCProjekt);
		$affected=$stmt->execute();
		if (false===$affected) {
			$stmt->closeCursor();
			throw new Exception($stmt->errorCode() . ':' . var_export($stmt->errorInfo(), true), 0);
		}
		$result=$stmt->fetch(PDO::FETCH_ASSOC);
		$stmt->closeCursor();
		if(!$result) {
			return null;
		}
		$o=new GCProjektModel();
		$o->assignByHash($result);
		$o->notifyPristine();
		return $o;
	}

	/**
	 * Bind all values to statement
	 *
	 * @param PDOStatement $stmt
	 */
	protected function bindValues(PDOStatement &$stmt) {
		$stmt->bindValue(1,$this->getIdCProjekt());
		$stmt->bindValue(2,$this->getRazeni());
		$stmt->bindValue(3,$this->getKod());
		$stmt->bindValue(4,$this->getText());
		$stmt->bindValue(5,$this->getPlnyText());
		$stmt->bindValue(6,$this->getValid());
	}


	/**
	 * Insert this instance into the database
	 *
	 * @param PDO $db
	 * @return mixed
	 */
	public function insertIntoDatabase(PDO $db) {
		if (null===$this->getIdCProjekt()) {
			$stmt=self::prepareStatement($db,self::SQL_INSERT_AUTOINCREMENT);
			$stmt->bindValue(1,$this->getRazeni());
			$stmt->bindValue(2,$this->getKod());
			$stmt->bindValue(3,$this->getText());
			$stmt->bindValue(4,$this->getPlnyText());
			$stmt->bindValue(5,$this->getValid());
		} else {
			$stmt=self::prepareStatement($db,self::SQL_INSERT);
			$this->bindValues($stmt);
		}
		$affected=$stmt->execute();
		if (false===$affected) {
			$stmt->closeCursor();
			throw new Exception($stmt->errorCode() . ':' . var_export($stmt->errorInfo(), true), 0);
		}
		$lastInsertId=$db->lastInsertId();
		if (false!==$lastInsertId) {
			$this->setIdCProjekt($lastInsertId);
		}
		$stmt->closeCursor();
		$this->notifyPristine();
		return $affected;
	}


	/**
	 * Update this instance into the database
	 *
	 * @param PDO $db
	 * @return mixed
	 */
	public function updateToDatabase(PDO $db) {
		$stmt=self::prepareStatement($db,self::SQL_UPDATE);
		$this->bindValues($stmt);
		$stmt->bindValue(7,$this->getIdCProjekt());
		$affected=$stmt->execute();
		if (false===$affected) {
			$stmt->closeCursor();
			throw new Exception($stmt->errorCode() . ':' . var_export($stmt->errorInfo(), true), 0);
		}
		$stmt->closeCursor();
		$this->notifyPristine();
		return $affected;
	}


	/**
	 * Delete this instance from the database
	 *
	 * @param PDO $db
	 * @return mixed
	 */
	public function deleteFromDatabase(PDO $db) {
		$stmt=self::prepareStatement($db,self::SQL_DELETE_PK);
		$stmt->bindValue(1,$this->getIdCProjekt());
		$affected=$stmt->execute();
		if (false===$affected) {
			$stmt->closeCursor();
			throw new Exception($stmt->errorCode() . ':' . var_export($stmt->errorInfo(), true), 0);
		}
		$stmt->closeCursor();
		return $affected;
	}

	/**
	 * Fetch GAUcastnikModel's which this GCProjektModel references.
	 * `c_projekt`.`id_c_projekt` -> `a_ucastnik`.`id_c_projekt_FK`
	 *
	 * @param PDO $db a PDO Database instance
	 * @param array $sort array of DSC instances
	 * @return GAUcastnikModel[]
	 */
	public function fetchGAUcastnikModelCollection(PDO $db, $sort=null) {
		$filter=array(GAUcastnikModel::FIELD_ID_C_PROJEKT_FK=>$this->getIdCProjekt());
		return GAUcastnikModel::findByFilter($db, $filter, true, $sort);
	}

	/**
	 * Fetch GSBehProjektuModel's which this GCProjektModel references.
	 * `c_projekt`.`id_c_projekt` -> `s_beh_projektu`.`id_c_projekt`
	 *
	 * @param PDO $db a PDO Database instance
	 * @param array $sort array of DSC instances
	 * @return GSBehProjektuModel[]
	 */
	public function fetchGSBehProjektuModelCollection(PDO $db, $sort=null) {
		$filter=array(GSBehProjektuModel::FIELD_ID_C_PROJEKT=>$this->getIdCProjekt());
		return GSBehProjektuModel::findByFilter($db, $filter, true, $sort);
	}

	/**
	 * Fetch GSysAccUsrProjektModel's which this GCProjektModel references.
	 * `c_projekt`.`id_c_projekt` -> `sys_acc_usr_projekt`.`id_c_projekt`
	 *
	 * @param PDO $db a PDO Database instance
	 * @param array $sort array of DSC instances
	 * @return GSysAccUsrProjektModel[]
	 */
	public function fetchGSysAccUsrProjektModelCollection(PDO $db, $sort=null) {
		$filter=array(GSysAccUsrProjektModel::FIELD_ID_C_PROJEKT=>$this->getIdCProjekt());
		return GSysAccUsrProjektModel::findByFilter($db, $filter, true, $sort);
	}

	/**
	 * Fetch GSysDbmDirModel's which this GCProjektModel references.
	 * `c_projekt`.`id_c_projekt` -> `sys_dbm_dir`.`id_c_projekt_FK`
	 *
	 * @param PDO $db a PDO Database instance
	 * @param array $sort array of DSC instances
	 * @return GSysDbmDirModel[]
	 */
	public function fetchGSysDbmDirModelCollection(PDO $db, $sort=null) {
		$filter=array(GSysDbmDirModel::FIELD_ID_C_PROJEKT_FK=>$this->getIdCProjekt());
		return GSysDbmDirModel::findByFilter($db, $filter, true, $sort);
	}

	/**
	 * Fetch GUcastnikModel's which this GCProjektModel references.
	 * `c_projekt`.`id_c_projekt` -> `ucastnik`.`id_c_projekt_FK`
	 *
	 * @param PDO $db a PDO Database instance
	 * @param array $sort array of DSC instances
	 * @return GUcastnikModel[]
	 */
	public function fetchGUcastnikModelCollection(PDO $db, $sort=null) {
		$filter=array(GUcastnikModel::FIELD_ID_C_PROJEKT_FK=>$this->getIdCProjekt());
		return GUcastnikModel::findByFilter($db, $filter, true, $sort);
	}

	/**
	 * Fetch GZajemceModel's which this GCProjektModel references.
	 * `c_projekt`.`id_c_projekt` -> `zajemce`.`id_c_projekt_FK`
	 *
	 * @param PDO $db a PDO Database instance
	 * @param array $sort array of DSC instances
	 * @return GZajemceModel[]
	 */
	public function fetchGZajemceModelCollection(PDO $db, $sort=null) {
		$filter=array(GZajemceModel::FIELD_ID_C_PROJEKT_FK=>$this->getIdCProjekt());
		return GZajemceModel::findByFilter($db, $filter, true, $sort);
	}


	/**
	 * get element as DOM Document
	 *
	 * @return DOMDocument
	 */
	public function toDOM() {
		return self::hashToDomDocument($this->toHash(), 'GCProjektModel');
	}

	/**
	 * get single GCProjektModel instance from a DOMElement
	 *
	 * @param DOMElement $node
	 * @return GCProjektModel
	 */
	public static function fromDOMElement(DOMElement $node) {
		$o=new GCProjektModel();
		$o->assignByHash(self::domNodeToHash($node, self::$FIELD_NAMES, self::$DEFAULT_VALUES, self::$FIELD_TYPES));
			$o->notifyPristine();
		return $o;
	}

	/**
	 * get all instances of GCProjektModel from the passed DOMDocument
	 *
	 * @param DOMDocument $doc
	 * @return GCProjektModel[]
	 */
	public static function fromDOMDocument(DOMDocument $doc) {
		$instances=array();
		foreach ($doc->getElementsByTagName('GCProjektModel') as $node) {
			$instances[]=self::fromDOMElement($node);
		}
		return $instances;
	}

}
?>