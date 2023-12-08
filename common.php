<?php
include 'components/connect.php';
require_once 'dbconfig.php';

class STUDENT {

	private $conn;

	public function __construct() {
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
	}

	public function runQuery( $sql ) {
		$stmt = $this->conn->prepare( $sql );
		return $stmt;
	}

	public function lasdID() {
		$stmt = $this->conn->lastInsertId();
		return $stmt;
	}


	public function razorPayOnline($property_id,$payment_type, $toValue,$razorpayOrderId,$razorpayPaymentId,$paymentStatus,$makerstamp,$updatestamp) {
		try {
			$stmt = $this->conn->prepare( "INSERT INTO payments(property_id,amount_paid,payment_type,razorpayOrderId, razorpayPaymentId, paymentStatus,makerstamp,updatestamp) 	
			VALUES(:property_id_o,:toValue_o,:payment_type_o,:razorpayOrderId_o,:razorpayPaymentId_o,:paymentStatus_o,:makerstamp_o,:updatestamp_o)" );
			$stmt->bindparam( ":property_id_o", $property_id ); 
  			$stmt->bindparam( ":payment_type_o", $payment_type ); 
			$stmt->bindparam( ":toValue_o", $toValue ); 
			$stmt->bindparam( ":razorpayOrderId_o", $razorpayOrderId );
			$stmt->bindparam( ":razorpayPaymentId_o", $razorpayPaymentId );
			$stmt->bindparam( ":paymentStatus_o", $paymentStatus );
			$stmt->bindparam( ":makerstamp_o", $makerstamp );
			$stmt->bindparam( ":updatestamp_o", $updatestamp );
			$stmt->execute();
			return $stmt;

		} catch ( PDOException $ex ) {
			echo $ex->getMessage();
		}
	}
	
	public function updatePayStatus($property_id,$razorpayOrderId, $razorpayPaymentId, $paymentStatus, $updatestamp) {
		try {
			$stmt = $this->conn->prepare( "UPDATE payments SET razorpayPaymentId=:razorpayPaymentId,paymentStatus=:paymentStatus,updatestamp=:updatestamp WHERE property_id=:property_id_o and razorpayOrderId='$razorpayOrderId'" );
			$stmt->bindparam( ":property_id_o", $property_id );
  			$stmt->bindparam( ":razorpayPaymentId", $razorpayPaymentId );
			$stmt->bindparam( ":paymentStatus", $paymentStatus );
  			$stmt->bindparam( ":updatestamp", $updatestamp );
			$stmt->execute();
			return $stmt;

		} catch ( PDOException $ex ) {
			echo $ex->getMessage();
		}
	}
}