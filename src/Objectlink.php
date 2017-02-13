<?php

namespace Lagan\Property;

/**
 * Controller for the Lagan object property.
 * Links to any object in the database and returns the object.
 *
 * A property type controller can contain a set, read, delete and options method. All methods are optional.
 * To be used with Lagan: https://github.com/lutsen/lagan
 */

class Objectlink {

	/**
	 * The set method is executed each time a property with this type is set.
	 *
	 * @param bean		$bean		The Redbean bean object with the property.
	 * @param array		$property	Lagan model property arrray.
	 * @param array		$new_value	Array with object type and id
	 *
	 * @return string
	 */
	public function set($bean, $property, $new_value) {

		// Validation
		if ( strtolower( $new_value['type'] ) == $bean->getMeta('type') && $new_value['id'] == $bean->id ) {

			throw new \Exception('An object can\'t link to itself.');

		} elseif ( $property['models'][ $new_value['type'] ] == 'single' && !$new_value['id'] ) {

			throw new \Exception( $new_value['type'] . ' can\'t link to a group of objects in this link.');

		} elseif ( $property['models'][ $new_value['type'] ] == 'group' && $new_value['id'] ) {

			throw new \Exception( $new_value['type'] . ' can\'t link to a single object in this link.');

		} elseif ( $new_value['id'] ) {

			// Check if object exists
			$object = \R::findOne( strtolower( $new_value['type'] ), ' id = :id ', [ ':id' => $new_value['id'] ] );
			if ( !$object ) {
				throw new \Exception('This '.$new_value['type'].' does not exist.');
			}

		}

		return json_encode( $new_value );

	}

	/**
	 * The read method is executed each time a property with this type is read.
	 *
	 * @param bean		$bean		The Readbean bean object with this property.
	 * @param string[]	$property	Lagan model property arrray.
	 *
	 * @return array	Array with keys 'type', 'id' 'data'. 'data' contains bean with type and id, or all beans of this type if id is empty.
	 */
	public function read($bean, $property) {

		if ( is_string( $bean->{ $property['name'] } ) && strlen( $bean->{ $property['name'] } ) > 0 ) {

			$object_data = json_decode( $bean->{ $property['name'] }, true );

			$model_name = '\Lagan\Model\\' . ucfirst( $object_data['type'] );
			$c = new $model_name();

			if ( empty( $object_data['id'] ) ) {
				$object_data['data'] = $c->read();
			} else {
				$object_data['data'] = $c->read( $object_data['id'] );
			}

			return $object_data;

		}

	}

	/**
	 * The options method returns all the optional values this property can have,
	 * including the one it currently has.
	 *
	 * @param bean		$bean		The Redbean bean object with the property.
	 * @param array		$property	Lagan model property arrray.
	 *
	 * @return array
	 */
	public function options($bean, $property) {

		// The $property['models'] is an array with which type of object models can be used as key,
		// and linktype as value. Linktype can be "single" to only link to singel objects,
		// "group" to only link to the entire group of objects, and "all" to link to both.
		foreach ( $property['models'] as $type => $link ) {
			if ( $link !== 'group' ) {
				$return[$type] = \R::findAll( strtolower( $type ) );
			}
		}

		return $return;
	}

}

?>