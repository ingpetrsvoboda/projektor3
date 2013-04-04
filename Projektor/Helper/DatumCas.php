<?php
class Projektor_Helper_DatumCas
{
	private $dateTime;

	const SQL_FORMAT = "Y-m-d";

	/**
         * Konstuktor objektu. Nastaví jako instanční typu PHP DateTime
         * @param DateTime $dateTime
         */
        private function __construct(DateTime $dateTime)
	{
		$this->dateTime = $dateTime;
	}

        /**
         * Factory metoda
         * @param string $retezecDatum Datum ve formátu českého data např "1.2.2001" nebo "01.02.2001", rozsah roků může být 1900 až 2999
         * @return \self
         */
        public static function zRetezce($retezecDatum=false)
        {
                $retezecDatum=trim($retezecDatum);
                $regex_pattern="/^(0?[1-9]|[12][0-9]|3[01])\.( 0?[1-9]|1[0-2])\.( (19|20)[0-9]{2})/";
                if(preg_match($regex_pattern, $retezecDatum, $regs) && checkdate($regs[2],$regs[1],$regs[3]))
                {
        		$datum = DateTime::createFromFormat(self::SQL_FORMAT, trim($regs[3])."-".trim($regs[2])."-".trim($regs[1]));
                	return new self($datum);
                }
        }

        /**
         * Factory metoda
         * @param string $sqlDatum Datuna čas ve fromátu užívaném MySQL
         * @return \self
         */
        public static function zSQL($sqlDatum)
	{
		$datum = DateTime::createFromFormat(self::SQL_FORMAT, $sqlDatum);
		return new self($datum);
	}

	/**
         * Factory metoda
         * @param array $pole Pole obsahující datum podle konvence užívané PEAR QuickForm array("Y" => "1234"; "m" => "03"; "d" => "19")
         * @return \self
         */
        public static function zQuickForm($pole)
	{
		$datum = DateTime::createFromFormat(self::SQL_FORMAT, $pole["Y"]."-".$pole["m"]."-".$pole["d"]);
		return new self($datum);
	}

	/**
         * Mtoda vrací hodnotu instanční proměnné
         * @return DateTime
         */
        public function dejDateTime()
	{
		return $this->dateTime();
	}

	/**
         * Metoda vrací pole obsahující datum podle konvence užívané PEAR QuickForm array("Y" => "1234"; "m" => "03"; "d" => "19")
         * @return array
         */
        public function dejDatumProQuickForm()
	{
		return array("Y" => $this->dateTime->format("Y"), "m" => $this->dateTime->format("m"), "d" => $this->dateTime->format("d"));
	}

	/**
         * Metoda vrací řetězec obsahující datum a čas ve formátu užívaném MySQL
         * @return string
         */
        public function dejDatumproSQL()
	{
		return $this->dateTime->format(self::SQL_FORMAT);
	}

        /**
         * Medoda vrací řetězec obsahující rok ve formátu "Y", např. "1999"
         * @return string
         */
        public function dejRok()
        {
            return $this->dateTime->format("Y");
        }
}