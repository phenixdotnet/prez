#!/bin/sh

kubectl delete configmap monapp-config-env-file
kubectl create configmap monapp-config-env-file --from-env-file=monapp_config/prod/.env

kubectl delete configmap monapp-config-consul-env-file
kubectl create configmap monapp-config-consul-env-file --from-env-file=monapp_config/prod_consul/.env