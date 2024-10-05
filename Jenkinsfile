pipeline {
    agent any

    environment {
        CHART_PATH = '.'                   // Path to Helm chart (root level)
        NAMESPACE = 'group3-project'        // Kubernetes namespace
        DOCKER_IMAGE = 'charlesprakash/capstone_project' // Replace with your Docker repository
        DOCKER_TAG = 'latest'               // Docker image tag
        DOCKER_CREDENTIALS_ID = 'dockerlogin' // Jenkins credentials ID for Docker login
    }

    stages {
        stage('Checkout') {
            steps {
                script {
                    checkout([$class: 'GitSCM', 
                        branches: [[name: '*/main']], // Adjust this if you're using a different branch
                        userRemoteConfigs: [[url: 'https://github.com/charlesprakash-git/Capstone-Project.git']]
                    ])
                }
            }
        }

        stage('Lint Helm Chart') {
            steps {
                script {
                    // Lint the Helm chart to catch any issues
                    sh "helm lint ${CHART_PATH}"
                }
            }
        }

        stage('Build Docker Image') {
            steps {
                script {
                    // Build the Docker image from the Dockerfile
                    sh "docker build -t ${DOCKER_IMAGE}:${DOCKER_TAG} ."
                }
            }
        }

        stage('Push Docker Image') {
            steps {
                script {
                    // Log in to Docker Hub or your Docker registry
                    withCredentials([usernamePassword(credentialsId: "${DOCKER_CREDENTIALS_ID}", usernameVariable: 'DOCKER_USER', passwordVariable: 'DOCKER_PASS')]) {
                        sh "echo ${DOCKER_PASS} | docker login -u ${DOCKER_USER} --password-stdin"
                    }
                    // Push the Docker image to the registry
                    sh "docker push ${DOCKER_IMAGE}:${DOCKER_TAG}"
                }
            }
        }

        stage('Create Namespace') {
            steps {
                script {
                    // Create the namespace if it does not exist
                    sh "kubectl get namespace ${NAMESPACE} || kubectl create namespace ${NAMESPACE}"
                }
            }
        }

        stage('Deploy Helm Chart') {
            steps {
                script {
                    // Deploy the Helm chart using the dependencies from the templates folder
                    sh "helm upgrade --install php-postgres ${CHART_PATH} --namespace ${NAMESPACE} --values ${CHART_PATH}/values.yaml --set image.repository=${DOCKER_IMAGE} --set image.tag=${DOCKER_TAG} --wait --timeout 300s"
                }
            }
        }

        stage('Verify Deployment') {
            steps {
                script {
                    // Verify that the deployment is successful by checking the pod status
                    sh "kubectl get pods --namespace ${NAMESPACE} -l app=php-postgres"
                }
            }
        }
    }

    post {
        success {
            echo 'Deployment successful!'
        }
        failure {
            echo 'Deployment failed.'
        }
    }
}
