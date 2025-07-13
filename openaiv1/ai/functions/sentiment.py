def analyze_sentiment(text):
    positive = ['senang', 'bahagia', 'suka', 'bagus', 'indah']
    negative = ['sedih', 'marah', 'benci', 'buruk', 'jelek']
    score = sum(1 for w in text.lower().split() if w in positive) - \
            sum(1 for w in text.lower().split() if w in negative)
    return {"sentiment": "positive" if score > 0 else "negative" if score < 0 else "neutral", "score": score}
