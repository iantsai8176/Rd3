# Rd3
API名稱

新增帳戶 addAccount {
    參數
        帳號 username
}

餘額 balance {
    參數
        帳號 username
}

轉帳 transfer {
    參數
        帳號 username
        轉入/轉出 IN/OUT
        金額 amount
}

轉帳確認 checkTransfer {
    參數
        帳號 username
        流水號 number
}

API URL https://plc-kmygrock666.c9users.io/Rd3/rdApi.php/