<?php
//
// +----------------------------------------------------------------------+
// | bitcommerce                                                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2007 bitcommerce.org                                   |
// |                                                                      |
// | http://www.bitcommerce.org                                           |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license        |
// +----------------------------------------------------------------------+
//  $Id: CommerceStatistics.php,v 1.5 2010/02/15 05:53:55 spiderr Exp $
//
	class CommerceStatistics extends BitBase {

		function getAggregateRevenue( $pParamHash ) {
			if( empty( $pParamHash['period'] ) ) {
				$pParamHash['period'] = 'Y-m';
			}
			if( empty( $pParamHash['max_records'] ) ) {
				$pParamHash['max_records'] = 12;
			}
			
			$ret = array();
			$ret['stats']['gross_revenue_max'] = 0;
			$ret['stats']['order_count_max'] = 0;

			$sql = "SELECT ".$this->mDb->SQLDate( $pParamHash['period'], '`date_purchased`' )." AS `hash_key`, ROUND( SUM( `order_total` ), 2 )  AS `gross_revenue`, COUNT( `orders_id` ) AS `order_count`, ROUND( SUM( `order_total` ) / COUNT( `orders_id` ), 2) AS `avg_order_size` 
					FROM " . TABLE_ORDERS . " WHERE `orders_status` > 0 GROUP BY `hash_key` ORDER BY `hash_key` DESC";
			$bindVars = array();
			if( $rs = $this->mDb->query( $sql, $bindVars, $pParamHash['max_records'] ) ) {
				while( $row = $rs->fetchRow() ) {
					$ret[$row['hash_key']] = $row;
					if( $ret['stats']['order_count_max'] < $row['order_count'] ) {
						$ret['stats']['order_count_max'] = $row['order_count'];
					}
					if( $ret['stats']['gross_revenue_max'] < $row['gross_revenue'] ) {
						$ret['stats']['gross_revenue_max'] = $row['gross_revenue'];
					}
				}
			}
			return( $ret );
		}

		function getProductRevenue( $pParamHash ) {
			switch( $pParamHash['period'] ) {
				case 'day':
				break;
			}
		}

		function getRevenueByOption( $pParamHash ) {
			$ret = array();

			$whereSql = '';

			$sql = "SELECT copa.`products_options_values_id` AS `hash_key`, copa.`products_options_id`, copa.`products_options`, COALESCE( cpa.`products_options_values_name`, copa.`products_options_values`) AS `products_options_values_name`, SUM(cop.`products_quantity` * copa.`options_values_price`) AS `total_revenue`, SUM(cop.`products_quantity`) AS `total_units`
					FROM " . TABLE_ORDERS . " co
						INNER JOIN " . TABLE_ORDERS_PRODUCTS . " cop ON(co.`orders_id`=cop.`orders_id`)
						INNER JOIN " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " copa ON(cop.`orders_products_id`=copa.`orders_products_id`)
						INNER JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " cpa ON(cpa.`products_options_values_id`=copa.`products_options_values_id`)
					WHERE co.`orders_status` > 0 $whereSql
					GROUP BY copa.`products_options_values_id`, copa.`products_options`, copa.`products_options_values`, cpa.`products_options_values_name`, copa.`products_options_id`
					ORDER BY copa.`products_options`, SUM(cop.`products_quantity`), copa.`products_options_values`";

			$ret = $this->mDb->getAll( $sql );
			return $ret;
		}

		function getRevenueByType( $pParamHash ) {
			$ret = array();

			$whereSql = '';

			$sql = "SELECT cpt.`type_id`, cpt.`type_name`, cpt.`type_class`, SUM(cop.`products_quantity` * cop.`products_price`) AS `total_revenue`, SUM(cop.`products_quantity`) AS `total_units`
					FROM " . TABLE_ORDERS . " co
						INNER JOIN " . TABLE_ORDERS_PRODUCTS . " cop ON(co.`orders_id`=cop.`orders_id`)
						INNER JOIN " . TABLE_PRODUCTS . " cp ON(cp.`products_id`=cop.`products_id`)
						INNER JOIN " . TABLE_PRODUCT_TYPES . " cpt ON(cpt.`type_id`=cp.`products_type`)
					WHERE co.`orders_status` > 0 $whereSql
					GROUP BY cpt.type_id, cpt.`type_name`, cpt.type_class
					ORDER BY SUM(cop.`products_quantity` * cop.`products_price`)";

			$ret = $this->mDb->getAssoc( $sql );
			return $ret;
		}
	}
?>

