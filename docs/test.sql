SELECT count(c0_.id) AS sclr_0 FROM chat c1_ INNER JOIN chat_message c0_ ON c1_.id = c0_.chat_id WHERE (c1_.owner_id = ? OR c1_.participant_id = ?) AND c0_.is_read = ? AND c0_.sender_id <> ?
