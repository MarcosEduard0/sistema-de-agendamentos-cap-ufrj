<?php

namespace app\components\bookings\exceptions;


class DateException extends \RuntimeException
{


	public static function invalidDate($date_string)
	{
		return new static(sprintf("Nenhuma data selecionada ou data inválida (%s).", $date_string));
	}


	public static function forSessionRange($datetime)
	{
		if ($datetime) {
			$dt = $datetime->format('d/m/Y');
		} else {
			$dt = 'Nenhuma';
		}

		return new static(sprintf("A data selecionada (%s), não está dentro do ano letivo atual.", $dt));
	}
}
