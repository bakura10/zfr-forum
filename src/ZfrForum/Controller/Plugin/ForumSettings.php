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

namespace ZfrForum\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * This controller plugin allow to retrieve the settings of the forum. It can either return the global settings or,
 * if they exist and if the owner of the forum allowed this feature, the settings overridden by the user
 */
class ForumSettings extends AbstractPlugin
{
    /**
     * @param bool $forceGlobals
     */
    public function __invoke($forceGlobals = false)
    {
        /**
         * Algorithme :
         *  1) Récupère les settings "globaux" (GlobalSettings) - ici il faudra sûrement implémenter un cache via
         *     Doctrine pour éviter de faire une nouvelle requête chaque fois au niveau du repository
         *  2) Si les settings globaux autorisent la surcharge des paramètres (c'est-à-dire si
         *     $globalSettings->canSettingsBeOverriddenByUser() === true) et que l'on ne force pas la récupération
         *     des settings globaux, on tente de récupérer les paramètres de l'utilisateur loggué
         *  3) S'ils n'existent pas, retourner les paramètres globaux, autrement retourner les paramètres de l'utilisateur
         */
    }
}
