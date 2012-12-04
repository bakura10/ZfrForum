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
use ZfrForum\Entity\ThreadTracking;
use ZfrForum\Mapper\ThreadTrackingMapperInterface;

class ThreadTrackingRepository extends EntityRepository implements ThreadTrackingMapperInterface
{
    /**
     * Create a thread tracking
     *
     * @param  ThreadTracking $threadTracking
     * @return ThreadTracking
     */
    public function create(ThreadTracking $threadTracking)
    {
        $this->_em->persist($threadTracking);
        $this->_em->flush($threadTracking);

        return $threadTracking;
    }

    /**
     * Update a thread tracking
     *
     * @param  ThreadTracking $threadTracking
     * @return ThreadTracking
     */
    public function update(ThreadTracking $threadTracking)
    {
        $this->_em->flush($threadTracking);
        return $threadTracking;
    }

    /**
     * Remove a thread tracking
     *
     * @param  ThreadTracking $threadTracking
     * @return void
     */
    public function remove(ThreadTracking $threadTracking)
    {
        $this->_em->remove($threadTracking);
        $this->_em->flush($threadTracking);
    }
}
