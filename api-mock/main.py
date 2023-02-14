# main.py
from fastapi import FastAPI
from dotenv import load_dotenv
from pydantic import BaseModel
import boto3
import botocore
import magic
import os, logging

DOWNLOAD_BASE_DIR="/work/download/"

app = FastAPI()

load_dotenv()
client = boto3.client("s3", endpoint_url=os.environ["AWS_ENDPOINT_URL"])

class ImageModel(BaseModel):
    image_path: str

@app.post("/")
async def mock(image_model: ImageModel):

    image_path = image_model.image_path;
    image_name = os.path.basename(image_path)
    local_path = DOWNLOAD_BASE_DIR + image_name

    try:
        client.download_file(
            os.environ["AWS_BUCKET_NAME"],
            image_model.image_path,
            local_path
            )

        minetype = magic.from_file(local_path, mime=True)

        logging.info(minetype)

        if minetype.startswith("image") and "success" in image_name : # minetypeがimage/xxx、かつ、ファイル名に「success」を含む場合は成功とする
            return {
                    "success": True,
                    "message": "success",
                    "estimated_data": {
                        "class": 3,          # 固定値
                        "confidence": 0.1234 # 固定値
                    }
                }
    except botocore.exceptions.ClientError as e:
        logging.error(e.response["Error"])


    return {
        "success": False,
        "message": "Error:E50012",
        "estimated_data": {}
    }
