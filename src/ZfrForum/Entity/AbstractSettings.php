<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace ZfrForum\Entity;

use Doctrine\ORM\Mapping;

/**
 * This mapped superclass describes all the available settings in the forum. This class is then extended by
 * GlobalSettings (that describes the global settings that are initially set) and by UserSettings (so that every
 * user can override those settings)
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractSettings
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected $numThreadsPerPage;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected $numMessagesPerThread;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $showSignatures;


    /**
     * Get the identifier
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set how many threads should be shown in every page
     *
     * @param  int $numThreadsPerPage
     * @return AbstractSettings
     */
    public function setNumThreadsPerPage($numThreadsPerPage)
    {
        $this->numThreadsPerPage = (int) $numThreadsPerPage;
        return $this;
    }

    /**
     * Get how many threads should be shown in every page
     *
     * @return int
     */
    public function getNumThreadsPerPage()
    {
        return $this->numThreadsPerPage;
    }

    /**
     * Set how many messages should be shown in a single page of a thread
     *
     * @param  int $numMessagesPerThread
     * @return AbstractSettings
     */
    public function setNumMessagesPerThread($numMessagesPerThread)
    {
        $this->numMessagesPerThread = (int) $numMessagesPerThread;
        return $this;
    }

    /**
     * Get how many messages should be shown in a single page of a thread
     *
     * @return int
     */
    public function getNumMessagesPerThread()
    {
        return $this->numMessagesPerThread;
    }

    /**
     * Set if signatures can be shown in messages
     *
     * @param  boolean $showSignatures
     * @return AbstractSettings
     */
    public function shouldShowSignatures($showSignatures)
    {
        $this->showSignatures = (bool) $showSignatures;
        return $this;
    }

    /**
     * Are the signatures shown in messages ?
     *
     * @return boolean
     */
    public function showSignatures()
    {
        return $this->showSignatures;
    }
}
