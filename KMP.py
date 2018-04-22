import sys
import json

topic = sys.argv[1]
key = sys.argv[2]

def KMP(text,pattern):
	partial_match = []	#partial match table
	spm = '' #string partial match
	for i in range(len(pattern)):
		#find prefix
		spm = spm + pattern[i]
		prefix = []
		s = ''
		for i in range(len(spm)-1):
			s = s + pattern[i]
			prefix.append(s)

		#find suffix
		suffix = []
		s = ''
		for i in reversed(range(1,len(spm))):
			s = pattern[i] + s
			suffix.append(s)

		#find the largest prefix which is suffix
		max_length = 0
		for i in range(len(prefix)):
			if prefix[i] == suffix[i]:
				max_length = len(prefix[i])

		partial_match.append(max_length)

	j = k = l = count = 0
	while (j < len(text)):
		l = j
		if(text[l]==pattern[k]):
			while (text[l]==pattern[k]) and (k < (len(pattern)-1)):
				l = l + 1
				k = k + 1
				count = count + 1

			if len(pattern) > (len(text)-j):	#jika panjang pattern lebih besar dari sisa text
				#print("String tidak ditemukan.")
				#break
				return -1
			elif text[l] != pattern[k]: #jika terdapat karakter yang berbeda
				k = 0
				j = j + (count - partial_match[count-1])
				count = 0
			else:
				break
		else:
			if len(pattern) > (len(text)-j):	#jika panjang pattern lebih besar dari sisa text
				#print("String tidak ditemukan.")
				#break
				return -1
			j = j + 1

	if len(pattern) <= (len(text)-j):	#jika panjang pattern lebih kecil atau sama dengan sisa text
		#print("String ditemukan di indeks ke -",j)
		return j

#Twitter API + KMP
def spamDetect(topic,keyword):
    #Setup Twitter API
    from twython import Twython
    APP_KEY = 'pVqijAxfbDRTWBadqEHs43Ozm'
    APP_SECRET = '5n0b7DDeCYCqeiHxk2vDNR6gurMcj3mEBAJQ6xIlStShY3R0DR'
    OAUTH_TOKEN = '985071710100598784-NTA548fEqcop86mOBV2bbHUOaDfz3X4'
    OAUTH_TOKEN_SECRET = 'uByR9KjsKdqLL5c1rmcUCmC3G9MNesoWwEuZ1Zf5sskLv'

    twitter = Twython(APP_KEY, APP_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET)

    #From search
    result = twitter.search(q=topic, count=30, lang='id')
    all_tweets = result['statuses']
       
    spamdata = []
    for tweet in all_tweets: 
        idx = KMP(str(tweet['text']).lower(), keyword)
        if (idx!=-1):
            spamdata.append('>>>SPAM<<<')
        else:
        	spamdata.append(" ")

    data = [ {
    	'time': all_tweets[i]['created_at'],
        'id' : all_tweets[i]['id_str'],
        'image': all_tweets[i]['user']['profile_image_url_https'],
        'username': all_tweets[i]['user']['screen_name'],
        'name': all_tweets[i]['user']['name'],
        'text': Twython.html_for_tweet(all_tweets[i]),
        'spam': spamdata[i]
    } for i in range(len(all_tweets))]

    with open('data.txt', 'w') as f:
    	json.dump(data, f)
    
spamDetect(topic,key.lower())