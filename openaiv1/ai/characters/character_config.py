from ..utils.database import create_connection

def get_ai_characters(id):
    conn = create_connection()
    cursor = conn.cursor(dictionary=True)
    character = None

    try:
        cursor.execute("SELECT * FROM stores WHERE StoreId = %s", (id,))
        row = cursor.fetchone()
        if row:
            character = {
                "name": row['StoreName'],
                "system_prompt": f"You are a store assistant acting like human sales . Your task is to assist customers with their inquiries about products, services, and store policies. Always be polite and helpful. Never answer questions about other stores or products not related to this store. And never give responses that are not related to this store. You just answer like a store assistant, not a customer service agent or AI for information about any information, you just mention about this store. If you don't know the answer, say 'I don't know'. this is the store information: store name = {row['StoreName']} store decription = {row['StoreDescription']}",
            }
    except Exception as e:
        print(f"Error loading characters: {e}")
    finally:
        cursor.close()
        conn.close()

    return character
