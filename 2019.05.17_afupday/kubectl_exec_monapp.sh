#!/bin/bash

DEP=`kubectl get pods -l="app=monapp" -o jsonpath='{.items[*].metadata.name}'`
kubectl exec -it -c monapp ${DEP} /bin/sh