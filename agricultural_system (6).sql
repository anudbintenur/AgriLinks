-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2025 at 06:33 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agricultural_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `DeliveryID` int(11) NOT NULL,
  `DeliveryType` varchar(50) DEFAULT NULL,
  `DepartureTime` datetime DEFAULT NULL,
  `ArrivalTime` datetime DEFAULT NULL,
  `DeliveryStatus` varchar(50) DEFAULT NULL,
  `WarehouseID` int(11) DEFAULT NULL,
  `Longitude` decimal(9,6) DEFAULT NULL,
  `Latitude` decimal(9,6) DEFAULT NULL,
  `LiveTimestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery`
--

INSERT INTO `delivery` (`DeliveryID`, `DeliveryType`, `DepartureTime`, `ArrivalTime`, `DeliveryStatus`, `WarehouseID`, `Longitude`, `Latitude`, `LiveTimestamp`) VALUES
(301, 'Internal', '2025-03-14 08:00:00', '2025-03-14 12:00:00', 'Completed', 1, 77.102500, 28.704100, '2025-04-25 18:46:32'),
(302, 'External', '2025-03-17 09:30:00', '2025-03-17 15:00:00', 'Pending', 2, 88.363900, 22.572600, '2025-04-25 18:46:32'),
(303, 'Internal', '2025-04-26 07:00:00', '2025-04-26 13:00:00', 'In Transit', 1, 77.594600, 12.971600, '2025-04-25 18:47:02');

-- --------------------------------------------------------

--
-- Table structure for table `driver`
--

CREATE TABLE `driver` (
  `DriverID` int(11) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `LicenseNumber` varchar(50) DEFAULT NULL,
  `ContactNum` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driver`
--

INSERT INTO `driver` (`DriverID`, `Name`, `LicenseNumber`, `ContactNum`) VALUES
(1, 'David Lee', 'DL123456', '444-555-6666'),
(2, 'Sarah Chen', 'SC654321', '444-777-8888'),
(3, 'Imran Khan', 'IK987654', '444-999-0000');

-- --------------------------------------------------------

--
-- Table structure for table `farmer`
--

CREATE TABLE `farmer` (
  `FarmerID` int(11) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `FarmLocation` varchar(255) DEFAULT NULL,
  `CropName` varchar(100) DEFAULT NULL,
  `CropType` varchar(100) DEFAULT NULL,
  `ContactNum` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmer`
--

INSERT INTO `farmer` (`FarmerID`, `Name`, `FarmLocation`, `CropName`, `CropType`, `ContactNum`) VALUES
(1, 'John Doe', 'Green Valley', 'Tomatoes', 'Vegetable', '123-456-7890'),
(2, 'Aisha Khan', 'Sunset Farms', 'Strawberries', 'Fruit', '987-654-3210'),
(3, 'Carlos Mendez', 'Riverbank', 'Potatoes', 'Vegetable', '456-123-7890');

-- --------------------------------------------------------

--
-- Table structure for table `farmer_harvest`
--

CREATE TABLE `farmer_harvest` (
  `FarmerID` int(11) NOT NULL,
  `BatchID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmer_harvest`
--

INSERT INTO `farmer_harvest` (`FarmerID`, `BatchID`) VALUES
(1, 101),
(2, 102),
(3, 103);

-- --------------------------------------------------------

--
-- Table structure for table `grading_criteria`
--

CREATE TABLE `grading_criteria` (
  `GradingCriteriaID` int(11) NOT NULL,
  `Weight` decimal(5,2) DEFAULT NULL,
  `ColorRequirement` varchar(100) DEFAULT NULL,
  `SizeRequirement` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grading_criteria`
--

INSERT INTO `grading_criteria` (`GradingCriteriaID`, `Weight`, `ColorRequirement`, `SizeRequirement`) VALUES
(1, 120.50, 'Bright Red', 'Medium'),
(2, 80.00, 'Deep Red', 'Small'),
(3, 60.00, 'Any', 'Mixed');

-- --------------------------------------------------------

--
-- Table structure for table `harvest`
--

CREATE TABLE `harvest` (
  `BatchID` int(11) NOT NULL,
  `CropName` varchar(100) DEFAULT NULL,
  `CropType` varchar(100) DEFAULT NULL,
  `DateOfHarvest` date DEFAULT NULL,
  `Weight` decimal(10,2) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `GradingCriteriaID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `harvest`
--

INSERT INTO `harvest` (`BatchID`, `CropName`, `CropType`, `DateOfHarvest`, `Weight`, `Quantity`, `GradingCriteriaID`) VALUES
(101, 'Tomatoes', 'Vegetable', '2025-03-12', 120.50, 300, 1),
(102, 'Strawberries', 'Fruit', '2025-03-15', 80.00, 200, 2),
(103, 'Potatoes', 'Vegetable', '2025-03-18', 150.00, 350, 1);

-- --------------------------------------------------------

--
-- Table structure for table `harvest_inspection_inspector`
--

CREATE TABLE `harvest_inspection_inspector` (
  `BatchID` int(11) DEFAULT NULL,
  `InspectorID` int(11) DEFAULT NULL,
  `InspectionID` int(11) NOT NULL,
  `Inspection_Date` date DEFAULT NULL,
  `Grade_Assigned` varchar(50) DEFAULT NULL,
  `Score_Color` int(11) DEFAULT NULL,
  `Score_Size` int(11) DEFAULT NULL,
  `Score_Weight` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `harvest_inspection_inspector`
--

INSERT INTO `harvest_inspection_inspector` (`BatchID`, `InspectorID`, `InspectionID`, `Inspection_Date`, `Grade_Assigned`, `Score_Color`, `Score_Size`, `Score_Weight`) VALUES
(101, 1, 1001, '2025-03-13', 'A', 9, 8, 10),
(102, 2, 1002, '2025-03-16', 'B', 7, 6, 8),
(103, 1, 1003, '2025-03-19', 'A', 8, 9, 9);

-- --------------------------------------------------------

--
-- Table structure for table `inspection`
--

CREATE TABLE `inspection` (
  `InspectorID` int(11) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Certification` varchar(100) DEFAULT NULL,
  `ContactNum` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inspection`
--

INSERT INTO `inspection` (`InspectorID`, `Name`, `Certification`, `ContactNum`) VALUES
(1, 'Maya Patel', 'Certified Food Inspector', '555-111-2222'),
(2, 'Alex Johnson', 'Agri Inspection Level II', '555-333-4444'),
(3, 'Ayesha Rahman', 'Organic Compliance Expert', '555-555-6666');

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE `package` (
  `PackageID` int(11) NOT NULL,
  `PackagingDate` date DEFAULT NULL,
  `PackageMaterial` varchar(100) DEFAULT NULL,
  `PackageWeight` decimal(10,2) DEFAULT NULL,
  `CurrentStatus` varchar(50) DEFAULT NULL,
  `StationID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package`
--

INSERT INTO `package` (`PackageID`, `PackagingDate`, `PackageMaterial`, `PackageWeight`, `CurrentStatus`, `StationID`) VALUES
(201, '2025-03-14', 'Plastic', 5.50, 'In Transit', 1),
(202, '2025-03-17', 'Cardboard', 6.20, 'Delivered', 2),
(203, '2025-04-01', 'Wood', 7.30, 'Pending', 3);

-- --------------------------------------------------------

--
-- Table structure for table `package_delivery`
--

CREATE TABLE `package_delivery` (
  `PackageID` int(11) NOT NULL,
  `DeliveryID` int(11) NOT NULL,
  `Weight` decimal(10,2) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package_delivery`
--

INSERT INTO `package_delivery` (`PackageID`, `DeliveryID`, `Weight`, `Quantity`) VALUES
(201, 301, 5.50, 50),
(202, 302, 6.20, 60),
(203, 303, 7.30, 70);

-- --------------------------------------------------------

--
-- Table structure for table `packaging_station`
--

CREATE TABLE `packaging_station` (
  `StationID` int(11) NOT NULL,
  `StationName` varchar(100) DEFAULT NULL,
  `Location` varchar(255) DEFAULT NULL,
  `ManagerName` varchar(100) DEFAULT NULL,
  `ContactNum` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packaging_station`
--

INSERT INTO `packaging_station` (`StationID`, `StationName`, `Location`, `ManagerName`, `ContactNum`) VALUES
(1, 'Main Pack House', 'Warehouse District', 'John Doe', '555-111-2222'),
(2, 'North Pack Center', 'North Valley', 'Jane Smith', '555-333-4444'),
(3, 'East Pack Hub', 'East Ridge', 'Michael Brown', '555-555-6666');

-- --------------------------------------------------------

--
-- Table structure for table `transport`
--

CREATE TABLE `transport` (
  `TransportID` int(11) NOT NULL,
  `BatchID` int(11) DEFAULT NULL,
  `StationID` int(11) DEFAULT NULL,
  `DepartureTime` datetime DEFAULT NULL,
  `ArrivalTime` datetime DEFAULT NULL,
  `Delivery_status` varchar(50) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `livetimestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `weight` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `vehicleID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transport`
--

INSERT INTO `transport` (`TransportID`, `BatchID`, `StationID`, `DepartureTime`, `ArrivalTime`, `Delivery_status`, `longitude`, `latitude`, `livetimestamp`, `weight`, `quantity`, `vehicleID`) VALUES
(1, 101, 1, '2025-04-28 06:45:00', '2025-04-28 14:20:00', 'Delivered', 77.102500, 28.704100, '0000-00-00 00:00:00', 1050.00, 60, 101),
(2, 102, 2, '2025-04-29 08:10:00', '2025-04-29 16:00:00', 'In Transit', 88.363900, 22.572600, '2025-04-25 15:31:23', 1200.50, 85, 102),
(3, 103, 1, '2025-04-30 07:30:00', '2025-04-30 15:00:00', 'Pending', 72.877700, 19.076000, '2025-04-25 16:00:01', 980.25, 70, 103);

-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE `vehicle` (
  `VehicleID` int(11) NOT NULL,
  `Capacity` int(11) DEFAULT NULL,
  `VehicleType` varchar(50) DEFAULT NULL,
  `NumberPlate` varchar(20) DEFAULT NULL,
  `DriverID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle`
--

INSERT INTO `vehicle` (`VehicleID`, `Capacity`, `VehicleType`, `NumberPlate`, `DriverID`) VALUES
(101, 4, 'Sedan', 'ABC-1234', 1),
(102, 2, 'Motorbike', 'XYZ-5678', 2),
(103, 6, 'Truck', 'LMN-9090', 3);

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

CREATE TABLE `warehouse` (
  `WarehouseID` int(11) NOT NULL,
  `Location` varchar(255) DEFAULT NULL,
  `Capacity` decimal(10,2) DEFAULT NULL,
  `StorageType` varchar(100) DEFAULT NULL,
  `WarehouseName` varchar(100) DEFAULT NULL,
  `ManagerName` varchar(100) DEFAULT NULL,
  `ContactNum` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warehouse`
--

INSERT INTO `warehouse` (`WarehouseID`, `Location`, `Capacity`, `StorageType`, `WarehouseName`, `ManagerName`, `ContactNum`) VALUES
(1, 'Central Storage', 5000.00, 'Refrigerated', 'Main Central Storage', 'Alice Cooper', '555-123-4567'),
(2, 'South Depot', 3000.00, 'Dry', 'Southside Depot', 'Bob Lee', '555-987-6543'),
(3, 'North Warehouse', 4500.00, 'Ambient', 'North Storage Facility', 'Charlie Brown', '555-555-7777');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`DeliveryID`),
  ADD KEY `WarehouseID` (`WarehouseID`);

--
-- Indexes for table `driver`
--
ALTER TABLE `driver`
  ADD PRIMARY KEY (`DriverID`);

--
-- Indexes for table `farmer`
--
ALTER TABLE `farmer`
  ADD PRIMARY KEY (`FarmerID`);

--
-- Indexes for table `farmer_harvest`
--
ALTER TABLE `farmer_harvest`
  ADD PRIMARY KEY (`FarmerID`,`BatchID`),
  ADD KEY `BatchID` (`BatchID`);

--
-- Indexes for table `grading_criteria`
--
ALTER TABLE `grading_criteria`
  ADD PRIMARY KEY (`GradingCriteriaID`);

--
-- Indexes for table `harvest`
--
ALTER TABLE `harvest`
  ADD PRIMARY KEY (`BatchID`),
  ADD KEY `fk_grading_criteria` (`GradingCriteriaID`);

--
-- Indexes for table `harvest_inspection_inspector`
--
ALTER TABLE `harvest_inspection_inspector`
  ADD PRIMARY KEY (`InspectionID`),
  ADD KEY `BatchID` (`BatchID`),
  ADD KEY `InspectorID` (`InspectorID`);

--
-- Indexes for table `inspection`
--
ALTER TABLE `inspection`
  ADD PRIMARY KEY (`InspectorID`);

--
-- Indexes for table `package`
--
ALTER TABLE `package`
  ADD PRIMARY KEY (`PackageID`),
  ADD KEY `StationID` (`StationID`);

--
-- Indexes for table `package_delivery`
--
ALTER TABLE `package_delivery`
  ADD PRIMARY KEY (`PackageID`,`DeliveryID`),
  ADD KEY `DeliveryID` (`DeliveryID`);

--
-- Indexes for table `packaging_station`
--
ALTER TABLE `packaging_station`
  ADD PRIMARY KEY (`StationID`);

--
-- Indexes for table `transport`
--
ALTER TABLE `transport`
  ADD PRIMARY KEY (`TransportID`) USING BTREE,
  ADD KEY `BatchID` (`BatchID`),
  ADD KEY `StationID` (`StationID`),
  ADD KEY `fk_vehicle` (`vehicleID`);

--
-- Indexes for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`VehicleID`),
  ADD KEY `DriverID` (`DriverID`);

--
-- Indexes for table `warehouse`
--
ALTER TABLE `warehouse`
  ADD PRIMARY KEY (`WarehouseID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `transport`
--
ALTER TABLE `transport`
  MODIFY `TransportID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `delivery`
--
ALTER TABLE `delivery`
  ADD CONSTRAINT `delivery_ibfk_2` FOREIGN KEY (`WarehouseID`) REFERENCES `warehouse` (`WarehouseID`);

--
-- Constraints for table `farmer_harvest`
--
ALTER TABLE `farmer_harvest`
  ADD CONSTRAINT `farmer_harvest_ibfk_1` FOREIGN KEY (`FarmerID`) REFERENCES `farmer` (`FarmerID`),
  ADD CONSTRAINT `farmer_harvest_ibfk_2` FOREIGN KEY (`BatchID`) REFERENCES `harvest` (`BatchID`);

--
-- Constraints for table `harvest`
--
ALTER TABLE `harvest`
  ADD CONSTRAINT `fk_grading_criteria` FOREIGN KEY (`GradingCriteriaID`) REFERENCES `grading_criteria` (`GradingCriteriaID`) ON UPDATE CASCADE;

--
-- Constraints for table `harvest_inspection_inspector`
--
ALTER TABLE `harvest_inspection_inspector`
  ADD CONSTRAINT `harvest_inspection_inspector_ibfk_1` FOREIGN KEY (`BatchID`) REFERENCES `harvest` (`BatchID`),
  ADD CONSTRAINT `harvest_inspection_inspector_ibfk_2` FOREIGN KEY (`InspectorID`) REFERENCES `inspection` (`InspectorID`);

--
-- Constraints for table `package`
--
ALTER TABLE `package`
  ADD CONSTRAINT `package_ibfk_1` FOREIGN KEY (`StationID`) REFERENCES `packaging_station` (`StationID`);

--
-- Constraints for table `package_delivery`
--
ALTER TABLE `package_delivery`
  ADD CONSTRAINT `package_delivery_ibfk_1` FOREIGN KEY (`PackageID`) REFERENCES `package` (`PackageID`),
  ADD CONSTRAINT `package_delivery_ibfk_2` FOREIGN KEY (`DeliveryID`) REFERENCES `delivery` (`DeliveryID`);

--
-- Constraints for table `transport`
--
ALTER TABLE `transport`
  ADD CONSTRAINT `fk_vehicle` FOREIGN KEY (`vehicleID`) REFERENCES `vehicle` (`VehicleID`),
  ADD CONSTRAINT `transport_ibfk_1` FOREIGN KEY (`BatchID`) REFERENCES `harvest` (`BatchID`),
  ADD CONSTRAINT `transport_ibfk_2` FOREIGN KEY (`StationID`) REFERENCES `packaging_station` (`StationID`);

--
-- Constraints for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD CONSTRAINT `vehicle_ibfk_1` FOREIGN KEY (`DriverID`) REFERENCES `driver` (`DriverID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
