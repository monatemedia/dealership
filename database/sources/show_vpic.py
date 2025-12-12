import sqlite3

db_path = "make_model.sqlite"

conn = sqlite3.connect(db_path)
cursor = conn.cursor()

# Get all table names
cursor.execute("SELECT name FROM sqlite_master WHERE type='table';")
tables = cursor.fetchall()

for (table_name,) in tables:
    print(f"\n=== Table: {table_name} ===")

    # Get column names
    cursor.execute(f"PRAGMA table_info({table_name});")
    columns = [col[1] for col in cursor.fetchall()]
    print("Columns:", columns)

    # Get row count
    cursor.execute(f"SELECT COUNT(*) FROM {table_name};")
    count = cursor.fetchone()[0]
    print("Row count:", count)

    # Get first 5 rows
    cursor.execute(f"SELECT * FROM {table_name} LIMIT 5;")
    rows = cursor.fetchall()
    for i, row in enumerate(rows, start=1):
        print(f"Row {i}: {row}")

conn.close()
