UPDATE `page_sections`
SET
  `title` = 'Menjadi rumah kebangsaan Muslim Indonesia yang progresif.',
  `title_en` = 'Becoming the national home of progressive Indonesian Muslims.',
  `vision` = 'Menjunjung tinggi nilai kebangsaan, Pancasila, dan semangat gotong royong untuk mendorong kehidupan beragama yang teduh, terbuka, dan menghargai kebhinnekaan.',
  `vision_en` = 'Upholding national values, Pancasila, and the spirit of mutual cooperation to promote a peaceful, open religious life that respects diversity.',
  `mission` = 'Memperkuat peran umat Islam dalam membangun Indonesia yang damai, demokratis, adil, dan berkeadaban. Dengan menjunjung tinggi nilai-nilai kebangsaan, Pancasila, serta semangat gotong royong, Bamusi mendorong terwujudnya kehidupan beragama yang teduh, terbuka, dan menghargai kebhinnekaan sebagai kekuatan bangsa.',
  `mission_en` = 'Strengthening the role of Muslims in building a peaceful, democratic, just, and civilized Indonesia. By upholding national values, Pancasila, and the spirit of mutual cooperation, Bamusi promotes a peaceful and open religious life that respects diversity as a strength of the nation.',
  `updated_at` = NOW()
WHERE `section_key` = 'visi';
