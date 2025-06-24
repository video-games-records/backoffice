<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use Exception;
use ProjetNormandie\ArticleBundle\Entity\Article;
use ProjetNormandie\ArticleBundle\Entity\ArticleTranslation;
use ProjetNormandie\ArticleBundle\Enum\ArticleStatus;
use ProjetNormandie\UserBundle\Entity\User;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    /**
     * @var array<mixed>
     */
    private array $articles = [
        [
            'id'    => 1,
            'status'    => ArticleStatus::PUBLISHED,
            'author_id' => 1,
            'translations' => [
                [
                    'locale' => 'fr',
                    'title' => 'Introduction à Vue.js: Le framework JavaScript progressif',
                    'content' => "<p>Vue.js est un framework JavaScript progressif utilisé pour construire des interfaces utilisateur. Contrairement à d'autres frameworks monolithiques, Vue est conçu pour être adopté de manière incrémentielle. Sa bibliothèque principale se concentre uniquement sur la couche de vue, ce qui facilite son intégration avec d'autres bibliothèques ou projets existants.</p><p>Les principales caractéristiques de Vue.js incluent:<br />- Un système de composants réutilisables<br />- Une réactivité puissante pour la gestion d'état<br />- Des transitions et animations intégrées<br />- Une faible empreinte (environ 20Ko)<br />- Une documentation excellente et une courbe d'apprentissage douce</p><p>Vue 3, la dernière version majeure, a introduit la Composition API, offrant une meilleure organisation du code et une réutilisation de la logique entre les composants.</p>"
                ],
                [
                    'locale' => 'en',
                    'title' => 'Introduction to Vue.js: The Progressive JavaScript Framework',
                    'content' => "<p>Vue.js is a progressive JavaScript framework used for building user interfaces. Unlike other monolithic frameworks, Vue is designed to be incrementally adoptable. Its core library focuses only on the view layer, making it easy to integrate with other libraries or existing projects.</p><p>Key features of Vue.js include:<br />- A reusable component system<br />- Powerful reactivity for state management<br />- Built-in transitions and animations<br />- Small footprint (around 20Kb)<br />- Excellent documentation and gentle learning curve</p><p>Vue 3, the latest major version, introduced the Composition API, offering better code organization and logic reuse between components.</p>"
                ]
            ]
        ],
        [
            'id'    => 2,
            'status'    => ArticleStatus::PUBLISHED,
            'author_id' => 1,
            'translations' => [
                [
                    'locale' => 'fr',
                    'title' => 'Symfony 6: Nouveautés et améliorations du framework PHP',
                    'content' => "<p>Symfony 6 représente une évolution majeure du célèbre framework PHP. Cette version apporte de nombreuses améliorations de performance et de nouvelles fonctionnalités tout en maintenant la stabilité qui a fait la réputation de Symfony.</p><p>Parmi les principales nouveautés:<br />- Support complet de PHP 8.1<br />- Amélioration du système de cache<br />- Nouveau composant Runtime pour une meilleure gestion des environnements d'exécution<br />- Refonte du système de formulaires<br />- Améliorations de la sécurité et de l'authentification</p><p>Le framework continue de promouvoir les bonnes pratiques de développement avec une architecture modulaire et des composants réutilisables qui peuvent être utilisés indépendamment.</p>"
                ],
                [
                    'locale' => 'en',
                    'title' => 'Symfony 6: New Features and Improvements in the PHP Framework',
                    'content' => "<p>Symfony 6 represents a major evolution of the famous PHP framework. This version brings numerous performance improvements and new features while maintaining the stability that has made Symfony's reputation.</p><p>Among the main new features:<br />- Full support for PHP 8.1<br />- Improved cache system<br />- New Runtime component for better management of execution environments<br />- Redesigned form system<br />- Security and authentication enhancements</p><p>The framework continues to promote good development practices with a modular architecture and reusable components that can be used independently.</p>"
                ]
            ]
        ],
        [
            'id'    => 3,
            'status'    => ArticleStatus::PUBLISHED,
            'author_id' => 1,
            'translations' => [
                [
                    'locale' => 'fr',
                    'title' => 'Shadcn UI: Une bibliothèque de composants React moderne et accessible',
                    'content' => "<p>Shadcn UI est une collection de composants React réutilisables qui combine l'élégance visuelle avec l'accessibilité et la personnalisation. Contrairement à d'autres bibliothèques UI, Shadcn n'est pas installé comme une dépendance - vous copiez et collez les composants dans votre projet, ce qui vous donne un contrôle total sur le code.</p><p>Caractéristiques principales:<br />- Composants accessibles suivant les normes ARIA<br />- Hautement personnalisables avec Tailwind CSS<br />- Thèmes clairs et sombres intégrés<br />- Styles cohérents et modernes<br />- Excellente documentation avec exemples</p><p>Les composants Shadcn sont parfaits pour les développeurs qui souhaitent un contrôle total sur leur interface utilisateur tout en bénéficiant de composants pré-construits et bien conçus.</p>"
                ],
                [
                    'locale' => 'en',
                    'title' => 'Shadcn UI: A Modern and Accessible React Component Library',
                    'content' => "<p>Shadcn UI is a collection of reusable React components that combines visual elegance with accessibility and customization. Unlike other UI libraries, Shadcn isn't installed as a dependency - you copy and paste the components into your project, giving you complete control over the code.</p><p>Key features:<br />- Accessible components following ARIA standards<br />- Highly customizable with Tailwind CSS<br />- Built-in light and dark themes<br />- Consistent and modern styling<br />- Excellent documentation with examples</p><p>Shadcn components are perfect for developers who want complete control over their UI while benefiting from pre-built, well-designed components.</p>"
                ]
            ]
        ],
        [
            'id'    => 4,
            'status'    => ArticleStatus::PUBLISHED,
            'author_id' => 1,
            'translations' => [
                [
                    'locale' => 'fr',
                    'title' => 'Tailwind CSS: Révolutionner le développement front-end avec des utilitaires CSS',
                    'content' => "<p>Tailwind CSS est un framework CSS utilitaire qui permet de construire rapidement des designs personnalisés sans quitter votre HTML. Contrairement aux frameworks comme Bootstrap, Tailwind ne propose pas de composants prédéfinis, mais plutôt un ensemble de classes utilitaires qui peuvent être composées pour créer n'importe quel design.</p><p>Avantages de Tailwind CSS:<br />- Développement plus rapide avec des classes prêtes à l'emploi<br />- Taille de fichier réduite en production grâce à PurgeCSS<br />- Personnalisation complète via un fichier de configuration<br />- Cohérence du design sur l'ensemble du projet<br />- Excellent pour les équipes avec une approche 'utility-first'</p><p>Avec Tailwind, vous pouvez créer des interfaces modernes et réactives sans écrire de CSS personnalisé, tout en gardant un contrôle total sur l'apparence de votre application.</p>"
                ],
                [
                    'locale' => 'en',
                    'title' => 'Tailwind CSS: Revolutionizing Front-end Development with CSS Utilities',
                    'content' => "<p>Tailwind CSS is a utility-first CSS framework that allows you to rapidly build custom designs without leaving your HTML. Unlike frameworks like Bootstrap, Tailwind doesn't provide predefined components, but rather a set of utility classes that can be composed to create any design.</p><p>Benefits of Tailwind CSS:<br />- Faster development with ready-to-use classes<br />- Reduced file size in production thanks to PurgeCSS<br />- Complete customization via a configuration file<br />- Design consistency across the entire project<br />- Great for teams with a utility-first approach</p><p>With Tailwind, you can create modern, responsive interfaces without writing custom CSS, while maintaining complete control over your application's appearance.</p>"
                ]
            ]
        ],
        [
            'id'    => 5,
            'status'    => ArticleStatus::PUBLISHED,
            'author_id' => 1,
            'translations' => [
                [
                    'locale' => 'fr',
                    'title' => 'Intégration de Vue.js avec Symfony: Le meilleur des deux mondes',
                    'content' => "<p>L'intégration de Vue.js avec Symfony permet de combiner la puissance d'un framework PHP robuste côté serveur avec un framework JavaScript réactif côté client. Cette combinaison offre une expérience de développement optimale pour créer des applications web modernes.</p><p>Pour intégrer Vue.js dans un projet Symfony:<br />- Utilisez Webpack Encore pour gérer vos assets JavaScript<br />- Créez des composants Vue pour les parties interactives de votre interface<br />- Utilisez les API Symfony pour fournir des données à vos composants Vue<br />- Profitez de la validation côté serveur de Symfony et de la réactivité côté client de Vue</p><p>Cette approche permet de développer des applications performantes qui offrent une excellente expérience utilisateur tout en maintenant une architecture backend solide et sécurisée.</p>"
                ],
                [
                    'locale' => 'en',
                    'title' => 'Integrating Vue.js with Symfony: The Best of Both Worlds',
                    'content' => "<p>Integrating Vue.js with Symfony allows you to combine the power of a robust PHP server-side framework with a reactive JavaScript framework on the client side. This combination offers an optimal development experience for creating modern web applications.</p><p>To integrate Vue.js into a Symfony project:<br />- Use Webpack Encore to manage your JavaScript assets<br />- Create Vue components for the interactive parts of your interface<br />- Use Symfony APIs to provide data to your Vue components<br />- Take advantage of Symfony's server-side validation and Vue's client-side reactivity</p><p>This approach allows you to develop high-performance applications that offer an excellent user experience while maintaining a solid and secure backend architecture.</p>"
                ]
            ]
        ],
        [
            'id'    => 6,
            'status'    => ArticleStatus::PUBLISHED,
            'author_id' => 1,
            'translations' => [
                [
                    'locale' => 'fr',
                    'title' => 'Construire des interfaces modernes avec Shadcn et Tailwind CSS',
                    'content' => "<p>La combinaison de Shadcn UI et Tailwind CSS offre une approche puissante pour construire des interfaces utilisateur modernes et accessibles. Shadcn fournit des composants React bien conçus tandis que Tailwind apporte la flexibilité de son système de classes utilitaires.</p><p>Avantages de cette combinaison:<br />- Des composants accessibles et personnalisables de Shadcn<br />- La flexibilité et la rapidité de développement de Tailwind<br />- Un design cohérent et moderne<br />- Une excellente expérience de développement<br />- Un contrôle total sur le code et le style</p><p>Pour commencer, installez Tailwind CSS dans votre projet, puis ajoutez les composants Shadcn dont vous avez besoin. Vous pouvez ensuite personnaliser les composants en utilisant les classes Tailwind ou en modifiant le thème Tailwind selon vos besoins.</p>"
                ],
                [
                    'locale' => 'en',
                    'title' => 'Building Modern Interfaces with Shadcn and Tailwind CSS',
                    'content' => "<p>The combination of Shadcn UI and Tailwind CSS offers a powerful approach to building modern and accessible user interfaces. Shadcn provides well-designed React components while Tailwind brings the flexibility of its utility class system.</p><p>Advantages of this combination:<br />- Accessible and customizable components from Shadcn<br />- Flexibility and rapid development from Tailwind<br />- Consistent and modern design<br />- Excellent development experience<br />- Complete control over code and styling</p><p>To get started, install Tailwind CSS in your project, then add the Shadcn components you need. You can then customize the components using Tailwind classes or by modifying the Tailwind theme according to your needs.</p>"
                ]
            ]
        ],
        [
            'id'    => 7,
            'status'    => ArticleStatus::PUBLISHED,
            'author_id' => 1,
            'translations' => [
                [
                    'locale' => 'fr',
                    'title' => 'Les meilleures pratiques Symfony en 2023',
                    'content' => "<p>Symfony continue d'évoluer et les meilleures pratiques pour développer des applications avec ce framework évoluent également. Voici les recommandations actuelles pour tirer le meilleur parti de Symfony en 2023.</p><p>Meilleures pratiques:<br />- Utiliser les attributs PHP 8 pour les contrôleurs et les entités<br />- Adopter l'injection de dépendances pour tous les services<br />- Implémenter des tests automatisés avec PHPUnit et Panther<br />- Utiliser Symfony UX pour améliorer l'expérience utilisateur<br />- Structurer votre application en modules ou en micro-services<br />- Exploiter le Messenger Component pour les tâches asynchrones</p><p>En suivant ces pratiques, vous pouvez développer des applications Symfony robustes, maintenables et performantes qui répondent aux exigences modernes du développement web.</p>"
                ],
                [
                    'locale' => 'en',
                    'title' => 'Symfony Best Practices in 2023',
                    'content' => "<p>Symfony continues to evolve, and the best practices for developing applications with this framework are evolving as well. Here are the current recommendations to get the most out of Symfony in 2023.</p><p>Best practices:<br />- Use PHP 8 attributes for controllers and entities<br />- Adopt dependency injection for all services<br />- Implement automated testing with PHPUnit and Panther<br />- Use Symfony UX to enhance user experience<br />- Structure your application into modules or microservices<br />- Leverage the Messenger Component for asynchronous tasks</p><p>By following these practices, you can develop robust, maintainable, and high-performance Symfony applications that meet modern web development requirements.</p>"
                ]
            ]
        ],
        [
            'id'    => 8,
            'status'    => ArticleStatus::PUBLISHED,
            'author_id' => 1,
            'translations' => [
                [
                    'locale' => 'fr',
                    'title' => 'Vue.js 3 Composition API: Une nouvelle façon d\'organiser votre code',
                    'content' => "<p>La Composition API est l\'une des fonctionnalités les plus importantes introduites dans Vue.js 3. Elle offre une approche alternative à l'API Options pour organiser la logique des composants, avec une meilleure réutilisation du code et une meilleure inférence de type TypeScript.</p><p>Principaux avantages de la Composition API:<br />- Organisation logique du code par fonctionnalité plutôt que par type d'option<br />- Réutilisation de la logique entre les composants via les 'composables'<br />- Meilleur support TypeScript<br />- Plus grande flexibilité dans l'organisation du code<br />- Performances améliorées pour les applications complexes</p><p>Pour utiliser la Composition API, vous définissez une fonction setup() qui expose les données réactives et les fonctions que votre template peut utiliser. C'est un changement de paradigme qui peut sembler complexe au début, mais qui offre des avantages significatifs pour les applications de grande envergure.</p>"
                ],
                [
                    'locale' => 'en',
                    'title' => 'Vue.js 3 Composition API: A New Way to Organize Your Code',
                    'content' => "<p>The Composition API is one of the most important features introduced in Vue.js 3. It offers an alternative approach to the Options API for organizing component logic, with better code reuse and improved TypeScript type inference.</p><p>Key benefits of the Composition API:<br />- Logical organization of code by feature rather than by option type<br />- Reuse of logic between components via 'composables'<br />- Better TypeScript support<br />- Greater flexibility in code organization<br />- Improved performance for complex applications</p><p>To use the Composition API, you define a setup() function that exposes reactive data and functions that your template can use. It's a paradigm shift that may seem complex at first, but offers significant benefits for large-scale applications.</p>"
                ]
            ]
        ],
        [
            'id'    => 9,
            'status'    => ArticleStatus::PUBLISHED,
            'author_id' => 1,
            'translations' => [
                [
                    'locale' => 'fr',
                    'title' => 'Optimisation des performances avec Tailwind CSS',
                    'content' => "<p>Bien que Tailwind CSS offre une expérience de développement exceptionnelle, il est important d'optimiser les performances de production. Voici comment tirer le meilleur parti de Tailwind tout en maintenant des temps de chargement rapides.</p><p>Stratégies d'optimisation:<br />- Utiliser PurgeCSS pour éliminer les classes inutilisées (intégré à Tailwind JIT)<br />- Activer le mode JIT (Just-In-Time) pour une génération à la demande<br />- Mettre en cache les fichiers CSS en production<br />- Utiliser la minification et la compression<br />- Considérer l'utilisation de Critical CSS pour le contenu visible<br />- Exploiter les fonctionnalités de fractionnement de code</p><p>Avec ces optimisations, Tailwind peut produire des fichiers CSS extrêmement légers en production, souvent plus petits que les approches CSS traditionnelles, tout en maintenant une excellente expérience de développement.</p>"
                ],
                [
                    'locale' => 'en',
                    'title' => 'Performance Optimization with Tailwind CSS',
                    'content' => "<p>While Tailwind CSS offers an exceptional development experience, it's important to optimize production performance. Here's how to get the most out of Tailwind while maintaining fast loading times.</p><p>Optimization strategies:<br />- Use PurgeCSS to eliminate unused classes (built into Tailwind JIT)<br />- Enable JIT (Just-In-Time) mode for on-demand generation<br />- Cache CSS files in production<br />- Use minification and compression<br />- Consider using Critical CSS for above-the-fold content<br />- Leverage code splitting features</p><p>With these optimizations, Tailwind can produce extremely lightweight CSS files in production, often smaller than traditional CSS approaches, while maintaining an excellent development experience.</p>"
                ]
            ]
        ],
        [
            'id'    => 10,
            'status'    => ArticleStatus::PUBLISHED,
            'author_id' => 1,
            'translations' => [
                [
                    'locale' => 'fr',
                    'title' => 'Créer une API RESTful avec Symfony et API Platform',
                    'content' => "<p>API Platform est un puissant framework construit sur Symfony qui simplifie la création d'APIs RESTful et GraphQL. Il permet de créer rapidement des APIs modernes avec un minimum de code tout en suivant les meilleures pratiques.</p><p>Fonctionnalités clés d'API Platform:<br />- Génération automatique d'API à partir des entités Doctrine<br />- Support complet de JSON-LD, Hydra, OpenAPI, GraphQL<br />- Validation automatique des données<br />- Pagination, filtrage et tri intégrés<br />- Documentation interactive générée automatiquement<br />- Sécurité robuste avec le composant Security de Symfony</p><p>Pour commencer avec API Platform, installez-le dans votre projet Symfony, annotez vos entités avec les métadonnées appropriées, et votre API est prête à être utilisée. C'est une solution idéale pour les projets qui nécessitent une API moderne et bien documentée.</p>"
                ],
                [
                    'locale' => 'en',
                    'title' => 'Building RESTful APIs with Symfony and API Platform',
                    'content' => "<p>API Platform is a powerful framework built on Symfony that simplifies the creation of RESTful and GraphQL APIs. It allows you to quickly create modern APIs with minimal code while following best practices.</p><p>Key features of API Platform:<br />- Automatic API generation from Doctrine entities<br />- Full support for JSON-LD, Hydra, OpenAPI, GraphQL<br />- Automatic data validation<br />- Built-in pagination, filtering, and sorting<br />- Automatically generated interactive documentation<br />- Robust security with Symfony's Security component</p><p>To get started with API Platform, install it in your Symfony project, annotate your entities with the appropriate metadata, and your API is ready to use. It's an ideal solution for projects that require a modern, well-documented API.</p>"
                ]
            ]
        ],
    ];


    private function updateGeneratorType(ObjectManager $manager): void
    {
        $metadata = $manager->getClassMetaData("ProjetNormandie\\ArticleBundle\\Entity\\Article");
        $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
    }

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $this->updateGeneratorType($manager);
        $this->loadArticles($manager);
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadArticles(ObjectManager $manager): void
    {
        foreach ($this->articles as $row) {
            $article = new Article();
            $article->setId($row['id']);
            $article->setStatus($row['status']);
            $article->setAuthor($this->getReference('user' . $row['author_id'], User::class));
            foreach ($row['translations'] as $translation) {
                $articleTranslation = new ArticleTranslation();
                $articleTranslation->setLocale($translation['locale']);
                $articleTranslation->setTitle($translation['title']);
                $articleTranslation->setContent($translation['content']);
                $article->addTranslation($articleTranslation);
            }
            $manager->persist($article);
            $this->addReference('article' . $article->getId(), $article);
        }
    }
}
