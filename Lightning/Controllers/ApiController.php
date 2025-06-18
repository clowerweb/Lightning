<?php
/**
 * API Controller for Lightning 2
 *
 * PHP version 8.2
 *
 * @since 2.0.0
 * @package Lightning
 */
declare(strict_types = 1);

namespace Lightning\Controllers;

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Exception;
use Throwable;

class ApiController extends BaseController {
    public function process(): array {
        try {
            $dataType = new ObjectType([
                'name' => 'Data',
                'fields' => [
                    'framework'   => ['type' => Type::string()],
                    'version'     => ['type' => Type::string()],
                    'timestamp'   => ['type' => Type::int()],
                    'php_version' => ['type' => Type::string()],
                    'installed'   => ['type' => Type::boolean()],
                ],
            ]);

            $statusType = new ObjectType([
                'name' => 'Status',
                'fields' => [
                    'success' => ['type' => Type::boolean()],
                    'message' => ['type' => Type::string()],
                    'data'    => ['type' => $dataType],
                ],
            ]);

            $authPayloadType = new ObjectType([
                'name' => 'AuthPayload',
                'fields' => [
                    'token' => ['type' => Type::string()],
                    'error' => ['type' => Type::string()],
                ],
            ]);

            $queryType = new ObjectType([
                'name' => 'Query',
                'fields' => [
                    'status' => [
                        'type' => $statusType,
                        'args' => [
                            'controller' => ['type' => Type::string()],
                            'method'     => ['type' => Type::string()],
                            'params'     => ['type' => Type::string()],
                        ],
                        'resolve' => function ($root, $args) {
                            $controllerName = $args['controller'] ?? 'TestApi';
                            $methodName = $args['method'] ?? 'test';
                            $params = isset($args['params']) ? json_decode($args['params'], true) : [];
                            $controllerClass = "App\\Controllers\\{$controllerName}Controller";

                            if (!class_exists($controllerClass)) {
                                throw new Exception("Controller not found: {$controllerClass}");
                            }

                            $controllerInstance = new $controllerClass();

                            if (!method_exists($controllerInstance, $methodName)) {
                                throw new Exception("Method not found: {$methodName}");
                            }

                            $result = $controllerInstance->$methodName($params);

                            return [
                                'success' => true,
                                'message' => $result,
                                'data' => [
                                    'framework' => 'Lightning PHP',
                                    'version'   => '2.0.0',
                                    'timestamp' => time(),
                                    'php_version' => PHP_VERSION,
                                    'installed' => $_ENV['INSTALLED'] === 'true',
                                ],
                            ];
                        },
                    ],
                ],
            ]);

            $mutationType = new ObjectType([
                'name' => 'Mutation',
                'fields' => [
                    'login' => [
                        'type' => $authPayloadType,
                        'args' => [
                            'username' => ['type' => Type::nonNull(Type::string())],
                            'password' => ['type' => Type::nonNull(Type::string())],
                        ],
                        'resolve' => function ($root, $args) {
                            $authController = new AuthController();
                            return $authController->login($args['username'], $args['password']);
                        },
                    ],
                    'createPage' => [
                        'type' => new ObjectType([
                            'name' => 'Page',
                            'fields' => [
                                'id' => ['type' => Type::int()],
                            ],
                        ]),
                        'args' => [
                            'title' => ['type' => Type::nonNull(Type::string())],
                            'slug' => ['type' => Type::nonNull(Type::string())],
                            'content' => ['type' => Type::string()],
                        ],
                        'resolve' => function ($root, $args) {
                            $entryController = new EntryController();
                            return $entryController->createPage($args);
                        },
                    ],
                ],
            ]);

            $schema = new Schema([
                'query' => $queryType,
                'mutation' => $mutationType,
            ]);

            $rawInput = file_get_contents('php://input');
            $input = json_decode($rawInput, true);

            if (empty($input['query'])) {
                throw new Exception('GraphQL query is missing.');
            }

            $query = $input['query'];
            $variableValues = $input['variables'] ?? null;

            $result = GraphQL::executeQuery($schema, $query, null, null, $variableValues);
            $output = $result->toArray();
        } catch (Throwable $e) {
            $output = [
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
        }

        return $output;
    }
}
