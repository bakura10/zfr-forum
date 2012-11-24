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
use ZfrForum\Service\PostService;

class PostController extends AbstractRestfulController
{
    /**
     * @var PostService
     */
    protected $postService;


    /**
     * Get the list of messages for a given thread
     *
     * @return mixed
     */
    public function getList()
    {
        /*$threadId = $this->params('threadId');
        $page     = $this->params()->fromQuery('page', 1);

        $posts = $this->getPostService()->getByThread($threadId);

        return array(
            'posts' => $posts->setCurrentPageNumber($page)
        );*/

        if ($this->request->isXmlHttpRequest()) {
            $threadId = $this->params('threadId');
            $page     = $this->params()->fromQuery('page', 1);

            $posts = $this->getPostService()->getByThread($threadId);

            $posts = $posts->getCurrentItems();

            return new JsonModel(array(
                'posts' => (array)$posts
            ));
        }
    }

    /**
     * Return single resource
     *
     * @param  mixed $id
     * @return mixed
     */
    public function get($id)
    {
        $post = $this->getPostService()->getById($id);

        return array(
            'post' => $post
        );
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
     * @return PostService
     */
    protected function getPostService()
    {
        if ($this->postService === null) {
            $this->postService = $this->serviceLocator->get('ZfrForum\Service\PostService');
        }

        return $this->postService;
    }
}
