<?php

namespace Phonf;

class DateField extends Field {

    public function setValue($value) {
        $this->value = $value;
    }

    /**
     * @return DateTime
     */
    public function getValue() {
        return $this->value;
    }

    public function getSpanishFormatValue() {
        if (is_null($this->getValue())) {
            return null;
        }
        if ($this->getValue() instanceof DateTime) {
            $dateTime = $this->getValue();
        } else {
            $dateTime = new DateTime($this->getValue());
        }
        return $dateTime->format("d/m/Y");
    }

    public function getEnglishFormatValue() {
        if (is_null($this->getValue())) {
            return null;
        }
        if ($this->getValue() instanceof DateTime) {
            $dateTime = $this->getValue();
        } else {
            $dateTime = new DateTime($this->getValue());
        }
        return $dateTime->format("Y-m-d");
    }

    public function getFormatedValue($format) {
        if (is_null($this->getValue())) {
            return null;
        }
        if ($this->getValue() instanceof DateTime) {
            $dateTime = $this->getValue();
        } else {
            $dateTime = new DateTime($this->getValue());
        }
        if ($format == "day") {
            $day = $dateTime->format("N");
            switch($day) {
                case 1:
                    return "Lunes";
                case 2:
                    return "Martes";
                case 3:
                    return "Miércoles";
                case 4:
                    return "Jueves";
                case 5:
                    return "Viernes";
                case 6:
                    return "Sábado";
                case 7:
                    return "Domingo";
            }
            return null;
        } else if ($format == "H:i") {
            $time = $dateTime->format("$format");
            if ($time == "00:00") {
                return "--:--";
            }
            return $time;
        }
        return $dateTime->format($format);
    }

    public function getDBValue() {
        if (!is_null($this->value)) {
            $statement = '"' . $this->getFormatedValue("Y-m-d H:i:s") . '"';
        } else {
            $statement = "null";
        }
        return $statement;

    }

}