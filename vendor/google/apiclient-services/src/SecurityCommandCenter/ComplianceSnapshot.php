<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\SecurityCommandCenter;

class ComplianceSnapshot extends \Google\Model
{
  /**
   * @var string
   */
  public $category;
  /**
   * @var string
   */
  public $cloudProvider;
  /**
   * @var string
   */
  public $complianceStandard;
  /**
   * @var string
   */
  public $complianceVersion;
  /**
   * @var string
   */
  public $count;
  /**
   * @var string
   */
  public $leafContainerResource;
  /**
   * @var string
   */
  public $name;
  /**
   * @var string
   */
  public $snapshotTime;

  /**
   * @param string
   */
  public function setCategory($category)
  {
    $this->category = $category;
  }
  /**
   * @return string
   */
  public function getCategory()
  {
    return $this->category;
  }
  /**
   * @param string
   */
  public function setCloudProvider($cloudProvider)
  {
    $this->cloudProvider = $cloudProvider;
  }
  /**
   * @return string
   */
  public function getCloudProvider()
  {
    return $this->cloudProvider;
  }
  /**
   * @param string
   */
  public function setComplianceStandard($complianceStandard)
  {
    $this->complianceStandard = $complianceStandard;
  }
  /**
   * @return string
   */
  public function getComplianceStandard()
  {
    return $this->complianceStandard;
  }
  /**
   * @param string
   */
  public function setComplianceVersion($complianceVersion)
  {
    $this->complianceVersion = $complianceVersion;
  }
  /**
   * @return string
   */
  public function getComplianceVersion()
  {
    return $this->complianceVersion;
  }
  /**
   * @param string
   */
  public function setCount($count)
  {
    $this->count = $count;
  }
  /**
   * @return string
   */
  public function getCount()
  {
    return $this->count;
  }
  /**
   * @param string
   */
  public function setLeafContainerResource($leafContainerResource)
  {
    $this->leafContainerResource = $leafContainerResource;
  }
  /**
   * @return string
   */
  public function getLeafContainerResource()
  {
    return $this->leafContainerResource;
  }
  /**
   * @param string
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
  /**
   * @param string
   */
  public function setSnapshotTime($snapshotTime)
  {
    $this->snapshotTime = $snapshotTime;
  }
  /**
   * @return string
   */
  public function getSnapshotTime()
  {
    return $this->snapshotTime;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ComplianceSnapshot::class, 'Google_Service_SecurityCommandCenter_ComplianceSnapshot');
