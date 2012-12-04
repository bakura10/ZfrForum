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

namespace ZfrForum\Repository;

use Doctrine\ORM\EntityRepository;
use ZfrForum\Entity\Category;
use ZfrForum\Mapper\CategoryMapperInterface;

class CategoryRepository extends EntityRepository implements CategoryMapperInterface
{
    /**
     * Create the category
     *
     * @param  Category $category
     * @return Category
     */
    public function create(Category $category)
    {
        $this->_em->persist($category);
        $this->_em->flush($category);

        return $category;
    }

    /**
     * Update the category
     *
     * @param  Category $category
     * @return Category
     */
    public function update(Category $category)
    {
        $this->_em->flush($category);
        return $category;
    }

    /**
     * Remove the category
     *
     * @param  Category $category
     * @return void
     */
    public function remove(Category $category)
    {
        $this->_em->remove($category);
        $this->_em->flush($category);
    }
}
