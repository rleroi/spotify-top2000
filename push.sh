#!/usr/bin/env bash
npm run build
docker login https://registry.ers.pw
docker buildx build --platform linux/amd64,linux/arm64 --push -t registry.ers.pw/top2000 .
