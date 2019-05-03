#!/bin/sh

kubectl create configmap monapp-config-env-file --from-env-file=monapp_config/prod/.env