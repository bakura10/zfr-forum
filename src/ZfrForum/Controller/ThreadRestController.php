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

namespace ZfrForum\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use ZfrForum\Entity\Thread;
use ZfrForum\Service\ThreadService;

class ThreadRestController extends AbstractRestfulController
{
    /**
     * @var array
     */
    protected $acceptCriteria = array(
        'Zend\View\Model\JsonModel' => array('application/json')
    );

    /**
     * @var ThreadService
     */
    protected $threadService;


    /**
     * Return list of resources
     *
     * @return mixed
     */
    public function getList()
    {
        $viewModel = $this->acceptableViewModelSelector($this->acceptCriteria);

        if ($viewModel instanceof JsonModel) {
            $threadsService = $this->getThreadService();
            $threads        = null;

            // We can have two cases : either we get the threads of a user, either we get the threads of
            // a category. We just need to check which parameter is set
            if ($categoryId = $this->params('categoryId', null)) {
                $threads = $threadsService->getByCategory($categoryId);
            } elseif ($userId = $this->params('userId', null)) {

            }

            $threads->setCurrentPageNumber($this->params()->fromQuery('page'), 1)
                    ->setItemCountPerPage($this->params()->fromQuery('limit'), 25);

            $data = array();
            $data['current_page'] = $threads->getCurrentPageNumber();
            $data['num_pages']    = count($threads);
            $data['total_items']  = $threads->getTotalItemCount();

            foreach ($threads as $thread) {
                $data['items'][] = array(
                    'id'       => $thread->getId(),
                    'title'    => $thread->getTitle(),
                    'category' => array(
                        'id' => $thread->getCategory()->getId()
                    ),
                    'createdBy' => array(
                        'id'          => $thread->getCreatedBy()->getId(),
                        'displayName' => $thread->getCreatedBy()->getDisplayName()
                    ),
                    'createdAt'  => $thread->getCreatedAt(),
                    'countViews' => $thread->getCountViews(),
                    'pinned'     => $thread->isPinned(),
                    'closed'     => $thread->isClosed()
                );
            }

            $viewModel->setVariables($data);
        }

        return $viewModel;
    }

    /**
     * Return single resource
     *
     * @param  mixed $id
     * @return Thread|null
     */
    public function get($id)
    {
        if ($this->request->isXmlHttpRequest()) {
            $thread = $this->getThreadService()->getById($id);

            return (new JsonModel(array('thread' => $thread)));
        }
    }

    /**
     * Create a new resource
     *
     * @param  mixed $data
     * @return mixed
     */
    public function create($data)
    {
        // TODO: Implement create() method.
    }

    /**
     * Update an existing resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return mixed
     */
    public function update($id, $data)
    {
        // TODO: Implement update() method.
    }

    /**
     * Delete an existing resource
     *
     * @param  mixed $id
     * @return mixed
     */
    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @return ThreadService
     */
    protected function getThreadService()
    {
        if ($this->threadService === null) {
            $this->threadService = $this->serviceLocator->get('ZfrForum\Service\ThreadService');
        }

        return $this->threadService;
    }
}
