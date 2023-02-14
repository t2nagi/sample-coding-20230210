#!/bin/ash

# 仮想環境は使用しない
pip install -r ./requirements.txt
uvicorn main:app --host 0.0.0.0 --reload --port 80