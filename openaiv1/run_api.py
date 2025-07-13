# run_api.py
import uvicorn


if __name__ == "__main__":
    uvicorn.run("ai.api:app", host="0.0.0.0", port=8435, reload=True)
