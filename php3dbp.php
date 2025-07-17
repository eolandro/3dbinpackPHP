<?php

function set_to_decimal($value,$number_of_decimals){
	return floatval(number_format(
			$value, $number_of_decimals
	));
}

function rect_intersect($item1, $item2, $x, $y){
		$d1 = $item1->get_dimension();
		$d2 = $item2->get_dimension();

		$cx1 = $item1->position[$x] + $d1[$x]/2;
		$cy1 = $item1->position[$y] + $d1[$y]/2;
		$cx2 = $item2->position[$x] + $d2[$x]/2;
		$cy2 = $item2->position[$y] + $d2[$y]/2;

		$ix = max($cx1, $cx2) - min($cx1, $cx2);
		$iy = max($cy1, $cy2) - min($cy1, $cy2);

		return ( ($ix < ($d1[$x]+$d2[$x])/2) and ($iy < ($d1[$y]+$d2[$y])/2) );
}

function intersect($item1, $item2){
	$Axis = new Axis();
	return (
		rect_intersect($item1, $item2, $Axis->WIDTH, $Axis->HEIGHT) and
		rect_intersect($item1, $item2, $Axis->HEIGHT, $Axis->DEPTH) and
		rect_intersect($item1, $item2, $Axis->WIDTH, $Axis->DEPTH)
	);
}

class RotationType{
	public $RT_WHD = 0;
	public $RT_HWD = 1;
	public $RT_HDW = 2;
	public $RT_DHW = 3;
	public $RT_DWH = 4;
	public $RT_WDH = 5;
	//public $ALL = [$RT_WHD, $RT_HWD, $RT_HDW, $RT_DHW, $RT_DWH, $RT_WDH];
	public $ALL = [0,1,2,3,4,5];
	
}

class Axis{
	public $WIDTH = 0;
	public $HEIGHT = 1;
	public $DEPTH = 2;
	//public $ALL = [$WIDTH, $HEIGHT, $DEPTH];
	public $ALL = [0,1,2];
}

class AuxMeth{
	
/*
	public function get_limit_number_of_decimals(number_of_decimals){
		return Decimal('1.{}'.format('0' * number_of_decimals))
	}


	public function set_to_decimal(value, number_of_decimals){
		number_of_decimals = get_limit_number_of_decimals(number_of_decimals)
		return Decimal(value).quantize(number_of_decimals)
	}
*/
}
class Item{
	public $name;
	public $width;
	public $height;
	public $depth;
	public $weight;
	public $rotation_type;
	public $position;
	public $number_of_decimals;
	
	public function __construct($name, $width, $height, $depth, $weight) {
		$this->name = $name;
		$this->width = $width;
		$this->height = $height;
		$this->depth = $depth;
		$this->weight = $weight;
		$this->rotation_type = 0;
		$this->position = [0, 0, 0];
		$this->number_of_decimals = 3;
	}


	public function format_numbers($number_of_decimals){
		$this->width = set_to_decimal($this->width, $number_of_decimals);
		$this->height = set_to_decimal($this->height, $number_of_decimals);
		$this->depth = set_to_decimal($this->depth, $number_of_decimals);
		$this->weight = set_to_decimal($this->weight, $number_of_decimals);
		$this->number_of_decimals = $number_of_decimals;
	}
	
	public function __toString(){
		$rp = "".$this->name."(".$this->width.", ".$this->height.", ".$this->depth;
		$rp = $rp."weight:".$this->weight.") pos (".$this->position[0].",".$this->position[1].",".$this->position[2].") rt (".$this->rotation_type.") vol (".$this->get_volume().")";
		return $rp;
	}
/*
	def string(self):
		return "%s(%sx%sx%s, weight: %s) pos(%s) rt(%s) vol(%s)" % (
			$this->name, $this->width, $this->height, $this->depth, $this->weight,
			$this->position, $this->rotation_type, $this->get_volume()
		)
*/
	public function get_volume(){
		return number_format(
			$this->width * $this->height * $this->depth, $this->number_of_decimals
		);
	}

	public function get_dimension(){
		$RotationType = new RotationType();
		$dimension = [];
		if ( $this->rotation_type == $RotationType->RT_WHD ):
			$dimension = [$this->width, $this->height, $this->depth];
		elseif ( $this->rotation_type == $RotationType->RT_HWD ):
			$dimension = [$this->height, $this->width, $this->depth];
		elseif ( $this->rotation_type == $RotationType->RT_HDW ):
			$dimension = [$this->height, $this->depth, $this->width];
		elseif ( $this->rotation_type == $RotationType->RT_DHW ):
			$dimension = [$this->depth, $this->height, $this->width];
		elseif ( $this->rotation_type == $RotationType->RT_DWH ):
			$dimension = [$this->depth, $this->width, $this->height];
		elseif ( $this->rotation_type == $RotationType->RT_WDH ):
			$dimension = [$this->width, $this->depth, $this->height];
		endif;
		return $dimension;
	}
}

class Bin{
	public function __construct($name, $width, $height, $depth, $max_weight){
		$this->name = $name;
		$this->width = $width;
		$this->height = $height;
		$this->depth = $depth;
		$this->max_weight = $max_weight;
		$this->items = [];
		$this->unfitted_items = [];
		//$this->number_of_decimals = DEFAULT_NUMBER_OF_DECIMALS
		$this->number_of_decimals = 3;
	}


	public function format_numbers($number_of_decimals){
		$this->width = set_to_decimal($this->width, $number_of_decimals);
		$this->height = set_to_decimal($this->height, $number_of_decimals);
		$this->depth = set_to_decimal($this->depth, $number_of_decimals);
		$this->max_weight = set_to_decimal($this->max_weight, $number_of_decimals);
		$this->number_of_decimals = $number_of_decimals;
	}
/*
	def string(self):
		return "%s(%sx%sx%s, max_weight:%s) vol(%s)" % (
			$this->name, $this->width, $this->height, $this->depth, $this->max_weight,
			$this->get_volume()
		)
*/
	public function __toString(){
		$rp = $this->name."(".$this->width."x".$this->height."x".$this->depth;
		$rp = $rp.", max_weight:".$this->max_weight.") vol (".$this->get_volume().")";
		return $rp;
	}
	
	public function get_volume(){
		return number_format(
			$this->width * $this->height * $this->depth, $this->number_of_decimals
		);
	}

	public function get_total_weight(){
		$total_weight = 0;
		
		foreach ($this->items as $item) {
			$total_weight += $item->weight;
		}
		return set_to_decimal($total_weight, $this->number_of_decimals);
	}
	
	public function put_item($item, $pivot){
		$fit = false;
		$valid_item_position = $item->position;
		$item->position = $pivot;
		
		$RotationType = new RotationType();
		
		for( $i = 0; $i < count($RotationType->ALL); $i++){
			$item->rotation_type = $i;
			$dimension = $item->get_dimension();
			
			$exitlp = $this->width < $pivot[0] + $dimension[0];
			$exitlp = $exitlp or $this->height < $pivot[1] + $dimension[1];
			$exitlp = $exitlp or $this->depth < $pivot[2] + $dimension[2];
			
			if ($exitlp){
				continue;
			}

			$fit = true;

			foreach ( $this->items  as $current_item_in_bin ){
				if (intersect($current_item_in_bin, $item) ){
					$fit = false;
					break;
				}
			}

			if ($fit){
				if ( ($this->get_total_weight() + $item->weight) > $this->max_weight ){
					$fit = false;
					return $fit;
				}
				$this->items[] = $item;
			}

			if (!$fit){
				$item->position = $valid_item_position;
			}

			return $fit;
		}
		if (!$fit){
			$item->position = $valid_item_position;
		}

		return $fit;
	}
}

class Packer{
	public $bins = [];
	public $items = [];
	public $unfit_items = [];
	public $total_items = 0;
	
	public function __construct(){
		$this->bins = [];
		$this->items = [];
		$this->unfit_items = [];
		$this->total_items = 0;
	}

	public function  add_bin($bin){
		#return $this->bins.append($bin) non sense
		$this->bins[] = $bin;
	}

	public function add_item($item){
		$this->total_items = count($this->items) + 1;
		// return $this->items.append(item) non sense
		$this->items[] = $item;
	}
	public function pack_to_bin($bin, $item){
		$fitted = false;
		if (!$bin->items):
			#$response = $bin.put_item($item, $START_POSITION)
			$response = $bin->put_item($item, [0,0,0]);
			if (!$response)
				$bin->unfitted_items[] = $item;
			return;
		endif;
		
		for( $axis = 0; $axis  < 3; $axis++ ){
			$Axis = new Axis();
			$items_in_bin = $bin->items;
			foreach($items_in_bin as $ib){
				$pivot = [0, 0, 0];
				[$w, $h, $d] = $ib->get_dimension();
				if ($axis == $Axis->WIDTH):
					$pivot = [
						$ib->position[0] + $w,
						$ib->position[1],
						$ib->position[2]
					];
				elseif ($axis == $Axis->HEIGHT):
					$pivot = [
						$ib->position[0],
						$ib->position[1] + $h,
						$ib->position[2]
					];
				elseif ($axis == $Axis->DEPTH):
					$pivot = [
						$ib->position[0],
						$ib->position[1],
						$ib->position[2] + $d
					];
				endif;

				if ($bin->put_item($item, $pivot)){
					$fitted = True;
					break;
				}
			}
			if ($fitted){
				break;
			}
		}
		if (!$fitted){
			$bin->unfitted_items[] = $item;
		}
	}

	#public function pack($bigger_first=False, $distribute_items=False,$number_of_decimals=DEFAULT_NUMBER_OF_DECIMALS){
	public function pack($bigger_first = false, $distribute_items = false,$number_of_decimals = 3){
		foreach( $this->bins as $bin){
			$bin->format_numbers($number_of_decimals);
		}

		foreach( $this->items as $item ){
			$item->format_numbers($number_of_decimals);
		}
		
		
		//bigger_first == reverse == rsort
		$cmp = function($abin,$bbin){
			$va = $abin->get_volume();
			$vb = $abin->get_volume();
			//var_dump([$va,$vb]);
			if ($va == $vb){
				return 0;
			}
			if ($va < $vb){
				return -1;
			}else{
				return 1;
			}
		};
		
		#$this->bins->sort(
		#	key=lambda bin: bin.get_volume(), reverse=bigger_first
		#)
		usort($this->bins,$cmp);
		if ($bigger_first){
			$this->bins = array_reverse($this->bins);
		}
		#$this->items.sort(
		#	key=lambda item: item.get_volume(), reverse=bigger_first
		#)
		usort($this->items,$cmp);
		if ($bigger_first){
			$this->items = array_reverse($this->items);
		}

		foreach( $this->bins as $bin ){
			foreach( $this->items as $item ){
				$this->pack_to_bin($bin, $item);
			}
			if ($distribute_items):
				foreach( $bin.items as $item){
					$this->items->remove($item);
				}
			endif;
		}
	}
}

?>
